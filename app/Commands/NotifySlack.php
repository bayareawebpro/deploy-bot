<?php

namespace App\Commands;
use App\Services\SlackApi;
use Illuminate\Console\Command;

class NotifySlack extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'notify:slack {message} {actionText?} {actionUrl?}';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Send a notification to slack with optional action button.';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        SlackApi::message(
            $this->argument('message'),
            $this->argument('actionText'),
            $this->argument('actionUrl')
        );
    }
}
