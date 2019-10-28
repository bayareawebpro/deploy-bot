<?php

namespace App\Commands;

use App\Commands\Traits\BashSuccess;
use App\Services\Bash;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Commands\Command;
class PreActivate extends Command
{
    use BashSuccess;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:activate {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Pre Activate New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        //php deploybot pre:activate "staging" "/home/forge/default/current" "XXX"

        $env = $this->argument('env');
        $hash= $this->argument('hash');
        $path= $this->argument('path');

        Bash::script("local", 'status/down', $path);

        Artisan::call('snapshots:run', [
            'hash' => $hash,
            'env' => $env,
        ]);

        if(in_array($env, ['staging'])){
            SlackApi::message("🛠 Migrating Database...");
            if($this->isSuccessful(
                Bash::script("local", 'deploy/migrate', $path)
            )){
                SlackApi::message("🧩 Database Migrated Successfully.");
            }else{
                SlackApi::message("🤬 Failed to Migrate Database!");
                abort(1);
            }
        }

        Bash::script("local", 'status/up', $path);
    }

    /**
     * Define the command's schedule.
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
