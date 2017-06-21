<?php
/**
 * This file is part of the Slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slave\Provider\Log;

use Monolog\Handler\StreamHandler;
use Pimple\Container;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

/**
 * Class LoggerProvider.
 */
class LoggerProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['logger'] = function ($c) {
            /** @var \Slave\Utils\Bag $bag */
            $bag = $c['bag'];

            $logger = new Logger($bag->getParameter('logger.channel'));

            // PSR 3 log message formatting
            $logger->pushProcessor(new PsrLogMessageProcessor());

            // log path
            $logPath = sprintf(
                '%s/%s_%s.log',
                $bag->getParameter('paths.sl_log_dir'),
                $bag->getParameter('logger.channel'),
                $bag->getParameter('environment')
            );

            // level
            $level = $this->translateLevel($bag->getParameter('logger.level'));

            // file handler with given configuration
            $handler = new StreamHandler($logPath, $level);
            $logger->pushHandler($handler);

            return $logger;
        };
    }

    /**
     * @param string|int $level
     *
     * @return int
     */
    protected function translateLevel($level)
    {
        // level is already translated to logger constant, return as-isx
        if (is_int($level)) {
            return $level;
        }

        $levels = Logger::getLevels();
        $upper = strtoupper($level);

        if (!isset($levels[$upper])) {
            throw new \InvalidArgumentException(sprintf(
                'Provided logging level "%s" does not exist. Must be a valid monolog logging level.',
                $level
            ));
        }

        return $levels[$upper];
    }
}
