<?php

namespace App\Commands;

use App\Services\Bash;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class UpdatePackage extends Command
{
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'update';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Update the package from GitHub.';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        Bash::script('local', "updatePackage", base_path())
            ->output()
            ->each(function($line){
                $this->line($line->buffer);
            });
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
