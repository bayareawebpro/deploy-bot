<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class RunAll extends Command
{
    /**
     * The signature of the command.
     * @var string
     */
    protected $signature = 'run:all';

    /**
     * The description of the command.
     * @var string
     */
    protected $description = 'Run All Deployment Commands';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $release = [
            'env' => 'production',
            'path' => '/Users/builder/Sites/test-project',
            'hash' => Str::random(12),
        ];

        $steps = [
            'pre:clone',
            'post:clone',
            'pre:install',
            'post:install',
            'pre:activate',
            'post:activate',
            'pre:purge',
            'post:purge',
        ];

        foreach ($steps as $step){
            $this->alert($step);
            $this->call($step, $release);
        }
    }
}
