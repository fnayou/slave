<?php

namespace Slave\Provider\Configuration;

use Symfony\Component\Config\Definition\Builder\VariableNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationSettings
 * @package Slave\Provider\Configuration
 */
class ConfigurationSettings implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');

        $rootNode->append($this->addApplicationNode());
        $rootNode->append($this->addParametersNode());
        $rootNode->append($this->addLoggerNode());

        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *         |\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function addApplicationNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('slave');

        $node
            ->children()
                ->scalarNode('name')
                    ->cannotBeEmpty()
                    ->isRequired()
                    ->defaultValue('Slave - minimalistic cli tool')
                    ->info('name of your slave (cli application)')
                ->end()
                ->scalarNode('version')
                    ->cannotBeEmpty()
                    ->isRequired()
                    ->defaultValue('1.0')
                    ->info('version of your slave (cli application)')
                ->end()
                ->scalarNode('description')
                    ->defaultValue('description not provided')
                    ->info('small description about your slave (cli application)')
                ->end()
            ->end();

        return $node;
    }

    /**
     * @return VariableNodeDefinition
     */
    public function addParametersNode()
    {
        $node = new VariableNodeDefinition('parameters');
        $node->info('globals parameters that you can define and use within your cli application');

        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     *         |\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function addLoggerNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('logger');

        $node
            ->info('monolog configuration')
            ->canBeEnabled()
            ->children()
                ->scalarNode('channel')
                    ->cannotBeEmpty()
                    ->defaultValue('main')
                    ->info('channel for logger')
                ->end()
                ->enumNode('level')
                    ->cannotBeEmpty()
                    ->values([
                        'DEBUG',
                        'INFO',
                        'NOTICE',
                        'WARNING',
                        'ERROR',
                        'CRITICAL',
                        'ALERT',
                        'EMERGENCY',
                        100,
                        200,
                        250,
                        300,
                        400,
                        500,
                        550,
                        600
                    ])
                    ->defaultValue('DEBUG')
                    ->info(<<<EOT
  logger level : DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY 
  or 
  100, 200, 250, 300, 400, 500, 550, 600.
EOT
                    )
                ->end()
            ->end();

        return $node;
    }
}
