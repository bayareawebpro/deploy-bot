<?php

namespace App\Commands;

use App\Services\Bash;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\BashSuccess;

class CreateSnapshot extends Command
{
    use BashSuccess;
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
        $this->alert( "Deploying Staging Database to Production");

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
                SlackApi::message("📸 Created Snapshot $hash from Staging Successfully. ($snapshot)");
            }else{
                SlackApi::message("✘ Failed to Create Snapshot!");
                exit(1);
            }
        }

        //Load Staging Snapshot into Production Database.
        if($this->isSuccessful(
            Bash::script("local", 'snapshots/load', "production $snapshot")
        )){
            SlackApi::message("🧩 Loaded Snapshot $hash to Production Successfully. ($snapshot)");
        }else{
            SlackApi::message("🤬 Failed to Load Snapshot!");
            exit(1);
        }

        //Cleaning Up Old Snapshots.
        if ($isNewRelease) {
            if($this->isSuccessful(
                Bash::script("local", 'snapshots/trim', "$path")
            )){
                SlackApi::message("🗑 Old Snapshots Cleaned Up Successfully.");
            }else{
                SlackApi::message("🤬 Failed to Clean Snapshots!");
                exit(1);
            }
        }
    }

    /**
     * Handle Staging Environment.
     * @param $hash
     */
    protected function staging($hash)
    {
        $this->alert("Creating Staging Database Snapshot");

        //Snapshots Directory / Insure Exists.
        $path = $this->makeDirectory(config('filesystems.disks.staging.root'));

        //Current Snapshot Path.
        $snapshot = "$path/$hash.sql";

        //Create Snapshot for Staging Database.
        if($this->isSuccessful(
            Bash::script("local", 'snapshots/dump', "staging $snapshot")
        )){
            SlackApi::message("📸 Staging Snapshot Created Successfully. ($snapshot)");
        }else{
            SlackApi::message("🤬 Failed to Create Snapshot!");
            exit(1);
        }

        //Cleaning Up Old Snapshots.
        if($this->isSuccessful(
            Bash::script("local", 'snapshots/trim', "$path")
        )){
            SlackApi::message("🗑 Old Snapshots Cleaned Up Successfully.");
        }else{
            SlackApi::message("🤬 Failed to Clean Snapshots!");
            exit(1);
        }
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
