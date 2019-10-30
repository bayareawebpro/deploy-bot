<?php

namespace App\Commands;

use App\Commands\Traits\CommandNotifier;
use App\Services\SlackApi;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\Bash;

class PreInstall extends Command
{
    use CommandNotifier;
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'pre:install {env} {path} {hash}';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = '3) Pre Install New Release';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        //deploybot pre:install "staging" "/home/forge/default/current" "XXX"
        //$path = $this->argument('path');
        //$hash = $this->argument('hash');
        //$env = $this->argument('env');
        //$url     = config("envoyer.$env.url");
        //$project = config("envoyer.$env.project");
        $this->notify("💉 Installing Dependencies...");
    }
}
