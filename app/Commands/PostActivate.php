<?php

namespace App\Commands;

use App\Services\Bash;
use App\Commands\Traits\CommandNotifier;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PostActivate extends Command
{
    use CommandNotifier;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'post:activate {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = '6) Post Activate New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');

        $this->notify("🛠 Flushing Caches...");

        if($this->isSuccessful(
            Bash::script("local", 'deploy/flush', $path)
        )){
            $this->notify("🗑 Caches Flushed Successfully.");
        }else{
            $this->error("🤬 Failed to Flush Caches!");
        }

        $this->notify("🛠 Priming Caches...");
        if($this->isSuccessful(
            Bash::script("local", 'deploy/prime', $path)
        )){
            $this->notify("🧩 Caches Primed Successfully.");
        }else{
            $this->error("🤬 Failed to Prime Caches!");
        }

        if(in_array($env, ['production'])) {
            $this->notify("🛠 Generating SiteMap...");
            if ($this->isSuccessful(
                Bash::script("local", 'deploy/sitemap', $path)
            )) {
                $this->notify("🧩 SiteMap Generated Successfully.");
            } else {
                $this->error("🤬 Failed to Generate SiteMap!");
            }
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
