<?php

namespace App\Commands;

use App\Services\Bash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Commands\Traits\CommandNotifier;
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
     * @return void
     */
    public function handle()
    {
        //Insure Plenty of Resources.
        @ini_set('max_execution_time', 900);
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
     * @return void
     */
    protected function production(string $hash)
    {
        $this->alert( "Deploying Staging Database to Production");

        //Check for Existing Release Snapshot.
        $isNewRelease = $this->isNewRelease($hash);

        //Snapshots Directory / Insure Exists.
        $path = $this->makeDirectory(config('filesystems.disks.production.root'));

        //Current Snapshot Path.
        $snapshot = "$path/$hash.sql";

        //Create Production Snapshot from Staging Database.
        if ($isNewRelease) {
            $this->snapshotCreate(config('envoyer.staging.database', 'staging'), $snapshot);
        }

        //Load Staging Snapshot into Production Database.
        $this->snapshotLoad(config('envoyer.production.database', 'production'), $snapshot);

        //Cleaning Up Old Snapshots.
        if ($isNewRelease) {
            $this->snapshotCleanup($path);
        }
    }

    /**
     * Handle Staging Environment.
     * @param $hash
     */
    protected function staging(string $hash)
    {
        $this->alert("Creating Staging Database Snapshot");

        //Insure Snapshots Directory Exists.
        $path = $this->makeDirectory(config('filesystems.disks.staging.root'));

        //Current Snapshot Path.
        $snapshot = "$path/$hash.sql";

        //Create Snapshot for Staging Database.
        if(!Storage::disk('staging')->exists("$hash.sql")){
            $this->snapshotCreate(config('envoyer.staging.database', 'staging'), $snapshot);
        }else{
            $this->notify("📸 Staging Snapshot Already Exists. ($snapshot)");
        }

        //Cleanup Snapshots Directory
        $this->snapshotCleanup($path);
    }

    /**
     * Snapshots Clean
     * @param string $path
     */
    protected function snapshotCleanup(string $path): void
    {
        if ($this->isSuccessful(
            Bash::script('snapshots/trim', "$path")
        )) {
            $this->notify("🗑 Old Snapshots Purged @ $path");
        } else {
            $this->error("🤬 Failed to Purge Snapshots @ $path");
        }
    }

    /**
     * Snapshot Create
     * @param string $database
     * @param string $path
     */
    protected function snapshotCreate(string $database, string $path): void
    {
        if ($this->isSuccessful(
            Bash::script('snapshots/dump', "$database $path")
        )) {
            $this->notify("📸 Created Snapshot Successfully: $database @ $path");
        } else {
            $this->error("🤬 Failed to Create Snapshot!");
        }
    }

    /**
     * Snapshot Load
     * @param string $database
     * @param string $path
     */
    protected function snapshotLoad(string $database, string $path): void
    {
        if ($this->isSuccessful(
            Bash::script('snapshots/load', "$database $path")
        )) {
            $this->notify("🧩 Loaded Snapshot Successfully: $database < $path");
        } else {
            $this->error("🤬 Failed to Load Snapshot!");
        }
    }

    /**
     * Make New Directory
     * @param string $path
     * @return string $path;
     */
    protected function makeDirectory(string $path): string
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
        return $path;
    }

    /**
     * @param $hash
     * @return bool
     */
    protected function isNewRelease(string $hash): bool
    {
        return (!Storage::disk('production')->exists("$hash.sql"));
    }
}
