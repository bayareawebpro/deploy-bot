<?php

namespace App\Commands;

use App\Services\Bash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Commands\Traits\CommandNotifier;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CreateSnapshot extends Command
{
    use CommandNotifier;

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
        }else{
            $this->error("Env: $env method does not exist.  Snapshots failed.");
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

        $stagDatabase = config('envoyer.staging.database', 'staging');
        $prodDatabase = config('envoyer.production.database', 'production');

        //Create Production Snapshot from Staging Database.
        if ($isNewRelease) {
            if($this->isSuccessful(
                Bash::script('snapshots/dump', "$stagDatabase $snapshot")
            )){
                $this->notify("📸 Created Snapshot $hash from Staging Successfully. ($snapshot)");
            }else{
                $this->error("🤬 Failed to Create Snapshot!");
            }
        }

        //Load Staging Snapshot into Production Database.
        if($this->isSuccessful(
            Bash::script('snapshots/load', "$prodDatabase $snapshot")
        )){
            $this->notify("🧩 Loaded Snapshot $hash to Production Successfully. ($snapshot)");
        }else{
            $this->error("🤬 Failed to Load Snapshot!");
        }

        //Cleaning Up Old Snapshots.
        if ($isNewRelease) {
            if($this->isSuccessful(
                Bash::script('snapshots/trim', "$path")
            )){
                $this->notify("🗑 Old Snapshots Purged Up Successfully.");
            }else{
                $this->error("🤬 Failed to Purge Snapshots!");
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

        if(!Storage::disk('staging')->exists("$hash.sql")){
            $stagDatabase = config('envoyer.staging.database', 'staging');
            //Create Snapshot for Staging Database.
            if($this->isSuccessful(
                Bash::script('snapshots/dump', "$stagDatabase $snapshot")
            )){
                $this->notify("📸 Staging Snapshot Created Successfully. ($snapshot)");
            }else{
                $this->error("🤬 Failed to Create Snapshot! ($snapshot)");
            }
        }else{
            $this->notify("📸 Staging Snapshot Already Exists. ($snapshot)");
        }

        //Cleaning Up Old Snapshots.
        if($this->isSuccessful(
            Bash::script('snapshots/trim', "$path")
        )){
            $this->notify("🗑 Old Snapshots Cleaned Up Successfully.");
        }else{
            $this->error("🤬 Failed to Clean Snapshots!");
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
