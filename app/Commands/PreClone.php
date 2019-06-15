<?php

namespace App\Commands;

use App\Commands\Traits\BashSuccess;
use App\Services\Bash;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PreClone extends Command
{
    use BashSuccess;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:clone {env} {release} {sha}';

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
        $release = $this->argument('release');
        $env = $this->argument('env');
        $sha = $this->argument('sha');

        $project = config("envoyer.$env.project");
        $url = config("envoyer.$env.url");

        SlackApi::message("💪 Deploying to $env!", "Envoyer.io", "https://envoyer.io/projects/$project");
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
