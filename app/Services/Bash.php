<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\App;
use Symfony\Component\Process\Process;
use Illuminate\Support\Collection;

class Bash
{

    /**
     * @var $process Process
     */
    protected $process;

    /**
     * @var $timeout int
     */
    protected $timeout;

    /**
     * @var $output Collection
     */
    protected $output;

    /**
     * Script On Host
     * @param string $script
     * @param string $arguments
     * @return $this
     */
    public static function script(string $script, string $arguments = '')
    {
        $script = resource_path("scripts/$script.sh");
        return app(Bash::class, [
            'command' => "/usr/bin/env bash {$script} {$arguments}",
        ])->run();
    }

    /**
     * Run Command
     * @param string|array $command
     */
    public function __construct($command)
    {
        $this->output = new Collection;
        $this->process = Process::fromShellCommandline($command);
        $this->process->setTimeout(600);
        $this->process->setTty(false);
        $this->process->setEnv(array(
            "PATH" => implode(':', config('bash.path', [])),
        ));
    }

    /**
     * Run Command
     * @return $this
     */
    public function run()
    {
        $this->process->run(function ($type, $buffer) {
            $this->output->push((object)array(
                'type'   => $type === Process::ERR ? 'error' : 'info',
                'buffer' => trim($buffer),
            ));
        });
        return $this;
    }

    /**
     * Is Successful
     * @return bool
     */
    public function isSuccessful()
    {
        return (
            $this->process->isSuccessful() &&
            $this->output->where('type', 'error')->count() === 0
        );
    }

    /**
     * Get Output
     * @return Collection
     */
    public function output()
    {
        return $this->output;
    }
}
