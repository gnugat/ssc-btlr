<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Symfony;

use Ssc\Btlr\App\Symfony\DependencyInjection\ContainerFactory;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

class CommandLoader
{
    public static function make(): CommandLoaderInterface
    {
        $container = ContainerFactory::make();
        $commands = [];
        foreach ($container->getParameter('console.command.ids') as $class) {
            if (true === str_contains($class, 'Flow')) {
                continue;
            }
            $commands[constant("{$class}::NAME")] = $class;
        }

        return new ContainerCommandLoader($container, $commands);
    }
}
