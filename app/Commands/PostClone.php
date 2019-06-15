<?php

namespace App\Commands;

use App\Commands\Traits\BashSuccess;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\Bash;

class PostClone extends Command
{
    use BashSuccess;

    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'post:clone {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Post Clone New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        //deploybot post:clone "staging" "/home/forge/default/current" "XXX"

        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');

        $project = config("envoyer.$env.project");
        $url = config("envoyer.$env.url");

        SlackApi::message("🛠 Compiling Assets...");

        if($this->isSuccessful(
            Bash::script("local", 'deploy/assets', $path)
        )){
            SlackApi::message("🧩 Assets Compiled Successfully.");
        }else{
            SlackApi::message("🤬 Failed to Compiled Assets!");
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
