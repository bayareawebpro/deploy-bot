<?php

namespace App\Commands;

use App\Commands\Traits\CommandNotifier;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Commands\Command;
use App\Services\Bash;

class PostInstall extends Command
{
    use CommandNotifier;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'post:install {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = '4) Post Install New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');

        $this->notify("🧩 Dependencies Installed Successfully!");
        $this->notify("🛠 Compiling Assets...");

        if($this->isSuccessful(Bash::script('deploy/assets', $path))){
            $this->notify("🧩 Assets Compiled Successfully.");
        }else{
            $this->error("🤬 Failed to Compile Assets!");
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
