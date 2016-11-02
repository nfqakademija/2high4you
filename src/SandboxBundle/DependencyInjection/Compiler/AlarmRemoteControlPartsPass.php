<?php
/**
 * Created by PhpStorm.
 * User: dejwas
 * Date: 16.10.31
 * Time: 14.28
 */

namespace SandboxBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AlarmRemoteControlPartsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // TODO: Implement process() method.

        if (!$container->has('app')){
            return;
        }

        $definition = $container->findDefinition('app.alarmremotecontrol');

        $taggedServices = $container->findTaggedServiceIds('app.alarmremotecontrol.parts');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addPart', array(new Reference($id)));
        }

    }
}