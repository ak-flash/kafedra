<?php

namespace App\Telegram;

use Exception;
use Illuminate\Support\Facades\Cache;
use Logger\TelegramHandler;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

/**
 * Class TelegramHandler
 * @package App\Logging
 */
class TelegramHandlerCustom extends TelegramHandler
{

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        $this->setErrorsCount();

        return new LineFormatter("%message%\n", null, false, true);
    }

    protected function setErrorsCount()
    {
        Cache::increment('logsErrorCount');
    }
}
