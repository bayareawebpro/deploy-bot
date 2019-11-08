<?php

namespace App\Commands;

use App\Services\Bash;
use App\Commands\Traits\CommandNotifier;
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
        $this->cacheFlush($path);
        $this->cachePrime($path);
        $this->sitemapGenerate($env, $path);
        $this->queueRestart($path);
    }

    /**
     * cache Flush
     * @param $path
     */
    protected function cacheFlush($path): void
    {
        $this->notify("🛠 Flushing Caches...");
        if ($this->isSuccessful(
            Bash::script('deploy/flush', $path)
        )) {
            $this->notify("🗑 Caches Flushed Successfully.");
        } else {
            $this->error("🤬 Failed to Flush Caches!");
        }
    }

    /**
     * cache Prime
     * @param $path
     */
    protected function cachePrime($path): void
    {
        $this->notify("🛠 Priming Caches...");
        if ($this->isSuccessful(
            Bash::script('deploy/prime', $path)
        )) {
            $this->notify("🧩 Caches Primed Successfully.");
        } else {
            $this->error("🤬 Failed to Prime Caches!");
        }
    }

    /**
     * queue Restart
     * @param $path
     */
    protected function queueRestart($path): void
    {
        $this->notify("🛠 Restarting Queues...");
        if ($this->isSuccessful(
            Bash::script('deploy/queue', $path)
        )) {
            $this->notify("🧩 Caches Primed Successfully.");
        } else {
            $this->error("🤬 Failed to Prime Caches!");
        }
    }

    /**
     * sitemap Generate
     * @param $env
     * @param $path
     */
    protected function sitemapGenerate($env, $path): void
    {
        if (in_array($env, ['production'])) {
            $this->notify("🛠 Generating SiteMap...");
            if ($this->isSuccessful(
                Bash::script('deploy/sitemap', $path)
            )) {
                $this->notify("🧩 SiteMap Generated Successfully.");
            } else {
                $this->error("🤬 Failed to Generate SiteMap!");
            }
        }
    }
}
