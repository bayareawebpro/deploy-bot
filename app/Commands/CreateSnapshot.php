<?php

namespace App\Commands;

use App\Services\Bash;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class CreateSnapshot extends Command
{
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'snapshots:run {env} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Run Database Snapshot Routines.';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        //Insure Plenty of Resources.
        @ini_set('max_execution_time', 10080);
        @ini_set('memory_limit', '500M');

        //Run the Command Sequence.
        $env = $this->argument('env');
        if (method_exists($this, $env)) {
            $this->$env($this->argument('hash'));
        }
    }

    /**
     * Handle Production Environment.
     * @param $hash
     */
    protected function production($hash)
    {
        //Alert Users.
        $alert = "Deploying Staging to Production";
        $this->alert($alert); SlackApi::message("*$alert*");

        //Check for Existing Release Snapshot.
        $isNewRelease = (!Storage::disk('production')->exists("$hash.sql"));

        //Snapshots Directory / Insure Exists.
        $path = $this->makeDirectory(config('filesystems.disks.production.root'));

        //Current Snapshot Path.
        $snapshot = "$path/$hash.sql";

        //Create Production Snapshot from Staging Database.
        if ($isNewRelease) {
            if($this->isSuccessful(
                Bash::script("local", 'snapshots/dump', "staging $snapshot")
            )){
                SlackApi::message("Created Snapshot $hash from Staging Successfully. ($snapshot)");
            }
        }

        //Load Production Snapshot into Live Database.
        if($this->isSuccessful(
            Bash::script("local", 'snapshots/load', "production $snapshot")
        )){
            SlackApi::message("Loaded Snapshot $hash to Production Successfully. ($snapshot)");
        }

        //Cleaning Up Old Snapshots.
        if ($isNewRelease) {
            if($this->isSuccessful(
                Bash::script("local", 'snapshots/trim', $path)
            )){
                SlackApi::message("Old Snapshots Cleaned Up Successfully.");
            }
        }
    }

    /**
     * Handle Staging Environment.
     * @param $hash
     */
    protected function staging($hash)
    {
        //Alert Users.
        $alert = "Creating Staging Database Snapshot";
        $this->alert($alert); SlackApi::message("*$alert*");

        //Snapshots Directory / Insure Exists.
        $path = $this->makeDirectory(config('filesystems.disks.staging.root'));

        //Current Snapshot Path.
        $snapshot = "$path/$hash.sql";

        //Create Snapshot for Staging Database.
        if($this->isSuccessful(
            Bash::script("local", 'snapshots/dump', "staging $snapshot")
        )){
            SlackApi::message("Staging Snapshot Created Successfully. ($snapshot)");
        }

        //Cleaning Up Old Snapshots.
        if($this->isSuccessful(
            Bash::script("local", 'snapshots/trim', $path)
        )){
            SlackApi::message("Old Snapshots Cleaned Up Successfully.");
        }
    }

    /**
     * Log Bash Results
     * @param Bash $result
     * @return bool
     */
    protected function isSuccessful(Bash $result)
    {
        $result->output()->each(function ($line) {
            $this->{$line->type}($line->buffer);
        });
        if (!$result->isSuccessful()) {
            SlackApi::message("An error was encountered. Process Aborted.");
            exit;
        }
        return $result->isSuccessful();
    }

    /**
     * Make New Directory
     * @param string $path
     * @return string $path;
     */
    protected function makeDirectory(string $path)
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
        return $path;
    }

    /**
     * Define the command's schedule.
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
