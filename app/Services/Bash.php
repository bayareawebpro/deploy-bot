<?php declare(strict_types=1);
namespace App\Services;
use Symfony\Component\Process\Process;
use Illuminate\Support\Collection;
class Bash{

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
     * @param string $host
     * @param string $script
     * @param string $arguments
     * @param int $timeout
     * @param bool $tty
     * @return $this
     */
    public static function script(
        string $host,
        string $script,
        string $arguments = '',
        int $timeout = 600,
        bool $tty = false
    ){
        $script = resource_path("scripts/$script.sh");
        if(in_array($host,['local', 'localhost'])){
            $command = "/bin/sh {$script} {$arguments}";
        }else{
            $command = "ssh {$host} \"bash -s\" < {$script} {$arguments}";
        }
        return (new self($command, $timeout, $tty))->run();
    }

    /**
     * Run Command
     * @param string|array $command
     * @param int $timeout
     * @param bool $tty
     */
    protected function __construct(
        $command,
        int $timeout = 600,
        bool $tty = false
    ){
        $this->output = new Collection;
        $this->process = Process::fromShellCommandline($command);
        $this->process->setTimeout($timeout);
        $this->process->setTty($tty);
        $this->process->setEnv(array(
            "PATH" => implode(':', config('bash.path', []))
        ));
    }

    /**
     * Run Command
     * @return $this
     */
    protected function run(){
        $this->process->run(function ($type, $buffer){
            $this->output->push((object) array(
                'type' => $type === Process::ERR ? 'error' : 'info',
                'buffer' => trim($buffer)
            ));
        });
        return $this;
    }

    /**
     * Is Successful
     * @return bool
     */
    public function isSuccessful(){
        return (
            $this->process->isSuccessful() &&
            $this->output->where('type', 'error')->count() === 0
        );
    }

    /**
     * Get Output
     * @return Collection
     */
    public function output(){
        return $this->output;
    }
}
