<?php

namespace App\Commands;

use App\Commands\Traits\CommandNotifier;
use App\Services\Bash;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Commands\Command;
class PreActivate extends Command
{
    use CommandNotifier;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:activate {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = '5) Pre Activate New Release';

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

        Bash::script('status/down', $path);

        Artisan::call('snapshots:run', [
            'hash' => $hash,
            'env' => $env,
        ]);

        if(in_array($env, ['staging'])){
            $this->notify("🛠 Migrating Database...");
            if($this->isSuccessful(
                Bash::script('deploy/migrate', $path)
            )){
                $this->notify("🧩 Database Migrated Successfully.");
            }else{
                $this->error("🤬 Failed to Migrate Database!");
            }
        }

        Bash::script('status/up', $path);
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
