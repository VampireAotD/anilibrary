<?php

declare(strict_types=1);

namespace App\Loggers\Logstash;

use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\SocketHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class LogstashHandler
 * @package App\Loggers\Logstash
 */
final class LogstashHandler
{
    public function __invoke(array $config): LoggerInterface
    {
        $handler = new SocketHandler($config['address']);
        $handler->setFormatter(new LogstashFormatter(config('app.name')));

        return new Logger('logstash', [$handler]);
    }
}
