<?php
/**
 * This file is part of the fnayou/slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou\Slave;

use Fnayou\Slave\Command\SlaveAboutInfoCommand;
use Fnayou\Slave\Command\SlaveAboutMasterCommand;
use Fnayou\Slave\Command\SlaveDebugContainerCommand;
use Fnayou\Slave\Command\SlaveDebugParametersCommand;
use Fnayou\Slave\Provider\Configuration\ConfigurationProvider;
use Fnayou\Slave\Provider\EventDispatcher\EventDispatcherProvider;
use Fnayou\Slave\Provider\Log\LoggerProvider;
use Pimple\Container;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;

/**
 * Class Application.
 */
class Application extends BaseApplication
{
    /**
     * @var string
     */
    const NAME = 'Slave cli tool';

    /**
     * @var string
     */
    const VERSION = '1.0';

    /** @var Container */
    protected $container;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);

        $this->getDefinition()->addOptions([
            new InputOption('--env', '-e', InputOption::VALUE_OPTIONAL, 'The environment to operate in', 'dev'),
            new InputOption('--no-debug', null, InputOption::VALUE_OPTIONAL, 'Switches off debug mode'),
        ]);

        $this->setContainer(new Container());

        $this->setBag(function () {
            return new Bag();
        });
    }

    /**
     * register default providers.
     */
    public function registerProviders()
    {
        // EventDispatcher Provider
        $this->getContainer()->register(new EventDispatcherProvider());
        $this->setDispatcher($this->getService('dispatcher'));

        // Configuration Provider
        $this->getContainer()->register(new ConfigurationProvider());

        // Monolog Provider
        if ($this->getBag()->get('logger.enabled')) {
            $this->getContainer()->register(new LoggerProvider());
        }

        // override name and version
        $this->setName($this->getBag()->get('slave.name'));
        $this->setVersion($this->getBag()->get('slave.version'));
    }

    /**
     * register commands.
     */
    public function registerCommands()
    {
        $this->addCommands([
            new SlaveAboutMasterCommand(),
            new SlaveAboutInfoCommand(),
            new SlaveDebugContainerCommand(),
            new SlaveDebugParametersCommand(),
        ]);

        if (!\class_exists('Symfony\Component\Finder\Finder')) {
            throw new \RuntimeException('You need the symfony/finder component to register commands.');
        }

        $srcDir = $this->getBag()->get('paths.sl_src_dir');

        $bundleFinder = new Finder();
        $bundleFinder->directories()->in($srcDir)->depth(0);

        /** @var \Symfony\Component\Finder\SplFileInfo $bundle */
        foreach ($bundleFinder as $bundle) {
            if (!\is_dir($bundleCommandDir = $srcDir.DIRECTORY_SEPARATOR.$bundle->getRelativePathname().DIRECTORY_SEPARATOR.'Command')) {
                continue;
            }
            $finder = new Finder();
            $finder->files()->name('*Command.php')->in($bundleCommandDir);

            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($finder as $file) {
                $commandClass = '\\'.$bundle->getRelativePathname().'\Command\\'.$file->getBasename('.php');
                $reflector = new \ReflectionClass($commandClass);
                if ($reflector->isSubclassOf('Symfony\\Component\\Console\\Command\\Command')
                    && !$reflector->isAbstract()
                    && !$reflector->getConstructor()->getNumberOfRequiredParameters()
                ) {
                    /** @var \Symfony\Component\Console\Command\Command $command */
                    $command = $reflector->newInstance();
                    $this->add($command);
                }
            }
        }
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getService($name)
    {
        if (!$this->getContainer()->offsetExists($name)) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot find service with id "%s"',
                $name
            ));
        }

        return $this->getContainer()->offsetGet($name);
    }

    /**
     * @param string   $name
     * @param \Closure $closure
     */
    public function setService($name, $closure)
    {
        $this->getContainer()->offsetSet($name, $closure);
    }

    /**
     * @return \Fnayou\Slave\Bag
     */
    public function getBag()
    {
        return $this->getContainer()->offsetGet('bag');
    }

    /**
     * @param \Closure $bag
     */
    public function setBag(\Closure $bag)
    {
        $this->getContainer()->offsetSet('bag', $bag);
    }
}
