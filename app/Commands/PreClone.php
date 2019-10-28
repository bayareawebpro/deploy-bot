<?php

namespace App\Commands;

use App\Services\SlackApi;
use App\Commands\Traits\BashSuccess;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PreClone extends Command
{
    use BashSuccess;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:clone {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Pre Clone New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        //deploybot pre:clone "staging" "/home/forge/default/current" "XXX"
        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');

        $project = config("envoyer.$env.project");

        $message = "💪 *Deployment to \"$env\" InProgress!*";
        $btnUrl = "https://envoyer.io/projects/$project";
        $btnText = "Envoyer.io";
        SlackApi::message($message, $btnText, $btnUrl);
        SlackApi::message("🌎 Downloading Code Repository...");
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
