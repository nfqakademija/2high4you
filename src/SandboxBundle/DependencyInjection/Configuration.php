<?php
/**
 * Created by PhpStorm.
 * User: dejwas
 * Date: 16.10.31
 * Time: 14.21
 */

namespace SandboxBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        // TODO: Implement getConfigTreeBuilder() method.

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sandbox');

        return $treeBuilder;
    }


}