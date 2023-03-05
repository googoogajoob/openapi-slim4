<?php

namespace Testserver\Factory;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class MonologFactory
{
    public static function createLogger() : LoggerInterface
    {
        $logger = new Logger('OpenApiSlim4Logger');
        $handler = new StreamHandler('/var/www/log/OpenApiSlim4.log', Logger::DEBUG);
        $logger->pushHandler($handler);

        return $logger;
    }
}