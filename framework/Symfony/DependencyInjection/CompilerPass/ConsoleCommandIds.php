<?php

namespace Ssc\Btlr\Framework\Symfony\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConsoleCommandIds implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->setParameter('console.command.ids', array_keys(
            $container->findTaggedServiceIds('console.command'),
        ));
    }
}
