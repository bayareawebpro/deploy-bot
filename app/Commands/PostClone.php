<?php

namespace App\Commands;

use App\Commands\Traits\CommandNotifier;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PostClone extends Command
{
    use CommandNotifier;

    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'post:clone {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = '2) Post Clone New Release';

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
        $this->notify("🧩 Repository Cloned Successfully.");
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
