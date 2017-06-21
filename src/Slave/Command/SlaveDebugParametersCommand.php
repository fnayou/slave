<?php
/**
 * This file is part of the fnayou/slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou\Slave\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SlaveDebugParametersCommand.
 */
class SlaveDebugParametersCommand extends Command
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
        $this->setName('slave:debug:parameters')
            ->setDescription('debug defined parameters')
            ->setHelp(
                <<<'EOT'
will output list of defined parameters with their values 
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

        $this->style->title('list of global parameters');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Fnayou\Slave\Application $application */
        $application = $this->getApplication();

        $parameters = $application->getBag()->getFlattenParameters();

        $headers = ['parameter', 'value'];
        $rows = [];

        foreach ($parameters as $parameter => $value) {
            $rows[] = [
                $parameter,
                $value,
            ];
        }

        $this->style->table($headers, $rows);
    }
}
