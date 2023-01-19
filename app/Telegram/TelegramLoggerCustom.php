<?php

namespace App\Telegram;

use Logger\TelegramLogger;
use Monolog\Logger;

/**
 * Class TelegramLogger
 * @package App\Logging
 */
class TelegramLoggerCustom extends TelegramLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        return new Logger(
            config('app.name'),
            [
                new TelegramHandlerCustom($config),
            ]
        );
    }
}
