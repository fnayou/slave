<?php
/**
 * This file is part of the fnayou/slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou\Slave\Provider\Configuration;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ConfigurationProvider.
 */
class ConfigurationProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     *
     * @throws \Exception
     */
    public function register(Container $container)
    {
        /** @var \Fnayou\Slave\Bag $bag */
        $bag = $container->offsetGet('bag');

        $paths = [];
        $paths['sl_root_dir'] = SLAVE_ROOT_DIR;
        $paths['sl_app_dir'] = $paths['sl_root_dir'].'/app';
        $paths['sl_cache_dir'] = $paths['sl_app_dir'].'/cache';
        $paths['sl_log_dir'] = $paths['sl_app_dir'].'/logs';
        $paths['sl_src_dir'] = $paths['sl_root_dir'].'/src';
        $paths['sl_tests_dir'] = $paths['sl_root_dir'].'/tests';

        $filesystem = new Filesystem();
        foreach ($paths as $path) {
            if (false === $filesystem->exists($path)) {
                $filesystem->mkdir($path);
            }
        }

        $cacheConfigFile = \sprintf(
            '%s/%s/%s.cache',
            $paths['sl_cache_dir'],
            $bag->get('environment'),
            \sha1($paths['sl_cache_dir'])
        );

        $cache = new ConfigCache($cacheConfigFile, $bag->get('debug'));

        if (false === $cache->isFresh() || 'dev' === $bag->get('environment')) {
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

            $cache->write(\serialize($parametersData), [$resource]);
        }

        $content = \unserialize(\file_get_contents($cacheConfigFile));

        $bag->setParameters(\array_merge(
            $bag->getParameters(),
            ['paths' => $paths],
            $content
        ));
    }
}
