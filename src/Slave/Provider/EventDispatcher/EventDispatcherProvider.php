<?php
/**
 * This file is part of the fnayou/slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou\Slave\Provider\EventDispatcher;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class EventDispatcherProvider.
 */
class EventDispatcherProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['dispatcher'] = function() {
            $dispatcher = new EventDispatcher();

            $dispatcher->addListener(ConsoleEvents::ERROR, function (ConsoleErrorEvent $event) {
                $style = new SymfonyStyle($event->getInput(), $event->getOutput());
                $command = $event->getCommand();
                $error = $event->getError();

                if ($error instanceof CommandNotFoundException) {
                    throw $error;
                }

                $style->error(\sprintf(
                    'Exception : Exception thrown while running command : %s',
                    $command->getName()
                ));

                $style->error(\sprintf(
                    '%s : %s',
                    $error->getCode(),
                    $error->getMessage()
                ));

                $style->error(\sprintf(
                    'File %s line %s',
                    $error->getFile(),
                    $error->getLine()
                ));

                $style->error(\sprintf(
                    '%s',
                    $error->getTraceAsString()
                ));
            });

            return $dispatcher;
        };
    }
}
