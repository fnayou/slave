<?php

namespace Slave\Provider\Configuration;

use Pimple\Container;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ConfigurationProvider
 * @package Slave\Provider\Configuration
 */
class ConfigurationProvider implements \Pimple\ServiceProviderInterface
{
    /**
     * @param Container $container
     * @throws \Exception
     */
    public function register(Container $container)
    {
        /** @var \Slave\Utils\Bag $bag */
        $bag = $container->offsetGet('bag');

        // define paths
        $paths = [];
        $paths['sl_root_dir'] = SLAVE_ROOT_DIR;
        $paths['sl_app_dir'] = $paths['sl_root_dir'].'/app';
        $paths['sl_cache_dir'] = $paths['sl_app_dir'].'/cache';
        $paths['sl_log_dir'] = $paths['sl_app_dir'].'/logs';
        $paths['sl_src_dir'] = $paths['sl_root_dir'].'/src';
        $paths['sl_tests_dir'] = $paths['sl_root_dir'].'/tests';

        // we make sure that paths exist, otherwise we create them
        $filesystem = new Filesystem();
        foreach ($paths as $path) {
            if ($filesystem->exists($path) === false) {
                $filesystem->mkdir($path);
            }
        }

        // cache file by environment.
        $cacheConfigFile = sprintf(
            '%s/%s/%s.cache',
            $paths['sl_cache_dir'],
            $bag->getParameter('environment'),
            sha1($paths['sl_cache_dir'])
        );

        $cache = new ConfigCache($cacheConfigFile, $bag->getParameter('debug'));

        if ($cache->isFresh() === false || $bag->getParameter('environment') === 'dev') {
            $directories = [$paths['sl_app_dir']];
            $locator = new FileLocator($directories);

            $loader = new YamlLoader($locator);
            $content = $loader->load($locator->locate('config.yml'));

            $processor = new Processor();
            $setting = new ConfigurationSettings();

            try {
                $parametersData = $processor->processConfiguration($setting, $content);
            } catch (\Exception $e) {
                throw $e;
            }

            $resource = new FileResource($paths['sl_app_dir'].'/config.yml');

            $cache->write(serialize($parametersData), [$resource]);
        }

        $content = unserialize(file_get_contents($cacheConfigFile));

        $bag->setParameters(array_merge(
            $bag->getRawParameters(),
            ['paths' => $paths],
            $content
        ));
    }
}
