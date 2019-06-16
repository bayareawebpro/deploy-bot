<?php

namespace App\Commands;

use App\Commands\Traits\BashSuccess;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\Bash;

class PostActivate extends Command
{
    use BashSuccess;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'post:activate {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Post Activate New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');


        SlackApi::message("🛠 Flushing Caches...");
        if($this->isSuccessful(
            Bash::script("local", 'deploy/flush', $path)
        )){
            SlackApi::message("🗑 Caches Flushed Successfully.");
        }else{
            SlackApi::message("🤬 Failed to Flush Caches!");
            exit(1);
        }

        SlackApi::message("🛠 Priming Caches...");
        if($this->isSuccessful(
            Bash::script("local", 'deploy/prime', $path)
        )){
            SlackApi::message("🧩 Caches Primed Successfully.");
        }else{
            SlackApi::message("🤬 Failed to Prime Caches!");
            exit(1);
        }

//        if(in_array($env, ['production'])) {
//            SlackApi::message("🛠 Generating SiteMap...");
//            if ($this->isSuccessful(
//                Bash::script("local", 'deploy/sitemap', $path)
//            )) {
//                SlackApi::message("🧩 SiteMap Generated Successfully.");
//            } else {
//                SlackApi::message("🤬 Failed to Generate SiteMap!");
//                exit(1);
//            }
//        }
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
