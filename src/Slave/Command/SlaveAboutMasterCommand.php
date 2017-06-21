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
 * Class SlaveAboutMasterCommand.
 */
class SlaveAboutMasterCommand extends Command
{
    const ASCII_ART = 'ICAgICA8aW5mbz48Y29tbWVudD58fF98fDwvY29tbWVudD4KIF9fX19fPGNvbW1lbnQ+fHxffHw8L2NvbW1lbnQ+Ci8gIF9fXzxjb21tZW50Pnx8X3x8PC9jb21tZW50PgpcIGAtLS0uPGNvbW1lbnQ+fF98fDwvY29tbWVudD4gX18gX19fICAgX19fX18KIGAtLS4gIFxfPGNvbW1lbnQ+fHw8L2NvbW1lbnQ+LyBfYCBcIFwgLyAvIF8gXAovXF9fLyAgLzxjb21tZW50Pl98fDwvY29tbWVudD4gKF98IHxcIFYgLyAgX18vClxfX19fXy88Y29tbWVudD58IHx8PC9jb21tZW50PlxfXyxffCBcXy8gXF9fX3w8L2luZm8+CiAgICAgICAgICAgICBtYWRlIGJ5IDxjb21tZW50PkF5bWVuIEZOQVlPVTwvY29tbWVudD4=';

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
        $this->style->text(\base64_decode(static::ASCII_ART, true));

        $this->style->newLine(1);
        $this->style->text('SLAVE is a small cli tool based on :');
        $this->style->listing([
            'symfony components',
            'pimple container',
            'monolog',
        ]);
        $this->style->note('Please feel free to contribute to the project if you want :  https://github.com/fnayou/slave ;)');
    }
}
