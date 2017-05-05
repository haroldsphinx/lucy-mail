<?php


namespace lucy\LucyMail\Model\Logger;

use Monolog\Logger as lucyLogger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/LucyMail.log';

    /**
     * Logging level
     * @var int
     */
    protected $loggerType = lucyLogger::INFO;
}
