<?php

namespace App\Commands;

use App\Services\SlackApi;
use App\Commands\Traits\CommandNotifier;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class PreClone extends Command
{
    use CommandNotifier;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:clone {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = '1) Pre Clone New Release';

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
        $this->notify("💪 Deployment to \"$env\" InProgress!");
        $this->notify("🌎 Downloading Code Repository...");
    }
}
