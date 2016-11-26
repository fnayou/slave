<?php
/**
 * This file is part of the Slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slave\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SlaveAboutMasterCommand.
 */
class SlaveAboutMasterCommand extends Command
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
        $this->setName('slave:about:master')
            ->setDescription('small description about SLAVE micro cli tool')
            ->setHelp(
                <<<'EOT'
will output some information about the SLAVE cli tool and a pretty ASCII art ;)
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

        $this->style->title('master information');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $asciiArt = <<<'EOT'
     <info><comment>||_||</comment>
 _____<comment>||_||</comment>
/  ___<comment>||_||</comment>
\ `---.<comment>|_||</comment> __ ___   _____
 `--.  \_<comment>||</comment>/ _` \ \ / / _ \
/\__/  /<comment>_||</comment> (_| |\ V /  __/
\_____/<comment>| ||</comment>\__,_| \_/ \___|</info>
             made by <comment>Aymen FNAYOU</comment>
EOT;

        $this->style->text($asciiArt);

        $this->style->newLine(1);
        $this->style->text('SLAVE is a small cli tool based on :');
        $this->style->listing([
            'symfony components',
            'pimple container',
            'monolog',
        ]);
        $this->style->note('Please feel free to contribute to the project if you want ;)');
    }
}
