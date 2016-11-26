<?php
/**
 * This file is part of the Slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slave\Provider\EventDispatcher;

use Pimple\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\Console\ConsoleEvents;

/**
 * Class EventDispatcherProvider.
 */
class EventDispatcherProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['dispatcher'] = function () {
            $dispatcher = new EventDispatcher();

            // add listener to handle exception
            $dispatcher->addListener(ConsoleEvents::EXCEPTION, function (ConsoleExceptionEvent $event) {
                $output = $event->getOutput();
                $command = $event->getCommand();

                $output->writeln(sprintf(
                    '<error>Exception :</error> Oops, exception thrown while running command : <info>%s</info>',
                    $command->getName()
                ));
            });

            return $dispatcher;
        };
    }
}
