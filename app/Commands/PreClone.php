<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PreClone extends Command
{
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:clone {env} {release}';

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
//        BTN="Envoyer.io";
//        URL="https://envoyer.io/projects/46981";
//        TEXT="*Staging Deployment In-Progress*";
//        php /home/forge/dbtool/dbtool notify:slack "$TEXT" "$BTN" "$URL";
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
