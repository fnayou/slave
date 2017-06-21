<?php
/**
 * This file is part of the fnayou/slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou\Slave\Provider\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Pimple\Container;

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
            /** @var \Fnayou\Slave\Bag $bag */
            $bag = $c['bag'];

            $logger = new Logger($bag->get('logger.channel'));

            // PSR 3 log message formatting
            $logger->pushProcessor(new PsrLogMessageProcessor());

            // log path
            $logPath = \sprintf(
                '%s/%s_%s.log',
                $bag->get('paths.sl_log_dir'),
                $bag->get('logger.channel'),
                $bag->get('environment')
            );

            // level
            $level = $this->translateLevel($bag->get('logger.level'));

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
        if (\is_int($level)) {
            return $level;
        }

        $levels = Logger::getLevels();
        $upper = \strtoupper($level);

        if (!isset($levels[$upper])) {
            throw new \InvalidArgumentException(sprintf(
                'Provided logging level "%s" does not exist. Must be a valid monolog logging level.',
                $level
            ));
        }

        return $levels[$upper];
    }
}
