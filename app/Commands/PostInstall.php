<?php

namespace App\Commands;

use App\Commands\Traits\BashSuccess;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Commands\Command;
use App\Services\Bash;

class PostInstall extends Command
{
    use BashSuccess;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'post:install {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Post Install New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');

        SlackApi::message("🧩 Dependencies Installed Successfully!");

        SlackApi::message("🛠 Compiling Assets...");
        if($this->isSuccessful(
            Bash::script("local", 'deploy/assets', $path)
        )){
            SlackApi::message("🧩 Assets Compiled Successfully.");
        }else{
            SlackApi::message("🤬 Failed to Compile Assets!");
            exit(1);
        }
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
