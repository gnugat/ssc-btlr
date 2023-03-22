<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerFactory
{
    private const COMPILER_CLASSES = [
        CompilerPass\ConsoleCommandIds::class,
    ];

    public static function make(): ContainerInterface
    {
        $container = new ContainerBuilder();

        (new ConfigLoader\YamlFiles())->loadConfig($container);
        foreach (self::COMPILER_CLASSES as $compilerClass) {
            $container->addCompilerPass(new $compilerClass());
        }
        $container->compile();

        return $container;
    }
}
