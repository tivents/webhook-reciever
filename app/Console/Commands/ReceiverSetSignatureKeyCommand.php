<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;

class ReceiverSetSignatureKeyCommand extends Command
{
    use ConfirmableTrait;
    protected $signature = 'receiver:set-signature-key';

    protected $description = 'Command description';

    public function handle(): void
    {
        $WEBHOOK_CLIENT_SECRET = Str::uuid()->toString();
        $this->setKeyInEnvironmentFile($WEBHOOK_CLIENT_SECRET);

        $this->laravel['config']['app.webhook_client_secret'] = $WEBHOOK_CLIENT_SECRET;

        $this->info('Webhook client secret key set successfully.');
        $this->info($WEBHOOK_CLIENT_SECRET);
    }

    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $currentKey = $this->laravel['config']['app.webhook_client_secret'];

        if (strlen($currentKey) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        if (! $this->writeNewEnvironmentFileWith($key)) {
            return false;
        }

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return bool
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        $replaced = preg_replace(
            $this->keyReplacementPattern(),
            'WEBHOOK_CLIENT_SECRET='.$key,
            $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null) {
            $this->error('Unable to set application key. No APP_KEY variable was found in the .env file.');

            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->laravel['config']['app.webhook_client_secret'], '/');

        return "/^WEBHOOK_CLIENT_SECRET{$escaped}/m";
    }

}
