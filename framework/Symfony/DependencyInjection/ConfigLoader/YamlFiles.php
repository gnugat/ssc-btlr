<?php

namespace Ssc\Btlr\Framework\Symfony\DependencyInjection\ConfigLoader;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class YamlFiles
{
    public function loadConfig(ContainerBuilder $container): void
    {
        $fileLocator = new FileLocator(__DIR__.'/../../../../config/');
        $loader = new DirectoryLoader($container, $fileLocator);
        $loader->setResolver(new LoaderResolver([
            new YamlFileLoader($container, $fileLocator),
            $loader,
        ]));
        $loader->load('dependency_injection/');
    }
}
