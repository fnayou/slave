<?php

namespace Slave\Provider\Configuration;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlLoader
 * @package Slave\Provider\Configuration
 */
class YamlLoader extends FileLoader
{
    /**
     * @param mixed $resource
     * @param null $type
     * @return array
     */
    public function load($resource, $type = null)
    {
        $content = Yaml::parse(file_get_contents($resource));

        return (array) $content;
    }

    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}
