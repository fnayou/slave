<?php

namespace Slave\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SlaveDebugContainerCommand
 * @package Slave\Command
 */
class SlaveDebugContainerCommand extends Command
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $style;

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('slave:debug:container')
            ->setDescription('debug container content')
            ->setHelp(
                <<<EOT
will output list of services of the container 
EOT
            );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Command\Command::initialize()
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);

        $this->style->title('list of services');
    }

    /**
     * @inheritdoc
     *
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Slave\Application $application */
        $application = $this->getApplication();

        $headers = ['service', 'namespace', 'path'];
        $rows = [];

        foreach ($application->getContainer()->keys() as $service) {
            $reflector = new \ReflectionClass(get_class($application->getContainer()->offsetGet($service)));

            $rows[] = [
                $service,
                $reflector->getName(),
                dirname($reflector->getFileName()),
            ];
        }

        $this->style->table($headers, $rows);
    }
}
