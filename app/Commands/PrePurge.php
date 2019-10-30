<?php

namespace App\Commands;

use App\Commands\Traits\CommandNotifier;
use App\Services\Bash;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PrePurge extends Command
{
    use CommandNotifier;

    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:purge {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = '7) Pre Purge Old Releases';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        $hash = $this->argument('hash');
        $env = $this->argument('env');

        $this->notify("🛠 Purging Old Releases...");
    }
}
