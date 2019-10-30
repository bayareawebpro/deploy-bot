<?php namespace App\Commands\Traits;
use App\Services\Bash;
use App\Services\SlackApi;
use Illuminate\Support\Str;

trait CommandNotifier{

    /**
     * Log Bash Results.
     * @param Bash $result
     * @return bool
     */
    public function isSuccessful(Bash $result)
    {
        $result->output()->each(function ($line) {
            if(!empty($line->buffer)){
                if($line->type === 'error'){
                    $this->warn($line->buffer);
                }else{
                    $this->line($line->buffer);
                }
            }
        });
        return $result->isSuccessful();
    }

    /**
     * Notify Slack & Log Output.
     * @param string $string
     */
    public function notify($string)
    {
        $this->info($string);
        if(config('slack.endpoint')){
            if(Str::contains($string, "Deployment Completed")){
                SlackApi::message($string, 'View Release', $this->getReleaseUrl());
            }else{
                SlackApi::message($string);
            }
        }
    }

    /**
     * Notify Slack & Log Output.
     * @param string $string
     */
    public function error($string, $verbosity = null)
    {
        if(config('slack.endpoint')){
            SlackApi::message($string);
        }
        abort(1, $string);
    }

    /**
     * Get Release URL.
     * @return string
     */
    protected function getReleaseUrl(){
        return (string) config("envoyer.{$this->argument('env')}.url");
    }
}
