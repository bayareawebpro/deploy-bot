<?php

namespace App\Commands;

use App\Commands\Traits\BashSuccess;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\Bash;

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
        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');

        $project = config("envoyer.$env.project");
        $url = config("envoyer.$env.url");

        SlackApi::message("✔ {$this->signature}.");
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
