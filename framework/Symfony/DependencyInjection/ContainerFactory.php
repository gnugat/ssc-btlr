<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Symfony\DependencyInjection;

use Ssc\Btlr\Framework\Symfony\DependencyInjection\CompilerPass\ConsoleCommandIds;
use Ssc\Btlr\Framework\Symfony\DependencyInjection\ConfigLoader\YamlFiles;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerFactory
{
    private const COMPILER_CLASSES = [
        ConsoleCommandIds::class,
    ];

    public static function make(): ContainerInterface
    {
        $container = new ContainerBuilder();

        (new YamlFiles())->loadConfig($container);
        foreach (self::COMPILER_CLASSES as $compilerClass) {
            $container->addCompilerPass(new $compilerClass());
        }
        $container->compile();

        return $container;
    }
}
