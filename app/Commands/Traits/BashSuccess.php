<?php namespace App\Commands\Traits;
use App\Services\Bash;

trait BashSuccess{
    /**
     * Log Bash Results
     * @param Bash $result
     * @return bool
     */
    protected function isSuccessful(Bash $result)
    {
        $result->output()->each(function ($line) {
            $this->{$line->type}($line->buffer);
        });
        return $result->isSuccessful();
    }
}
