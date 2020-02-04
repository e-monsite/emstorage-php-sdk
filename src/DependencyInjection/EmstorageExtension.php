<?php

declare(strict_types=1);

namespace Emonsite\Emstorage\PhpSdk\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EmstorageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__.'/../../config');
        $loaderResolver = new LoaderResolver([
            new GlobFileLoader($container, $fileLocator),
            new YamlFileLoader($container, $fileLocator),
            new PhpFileLoader($container, $fileLocator),
        ]);
        $delegatingLoader = new DelegatingLoader($loaderResolver);

        $env = $container->getParameter('kernel.environment');

        $delegatingLoader->load('{packages}/*.{php,yaml,yml}', 'glob');
        $delegatingLoader->load('{packages}/'.$env.'/**/*.{php,yaml,yml}', 'glob');
        $delegatingLoader->load('{services}.{php,yaml,yml}', 'glob');
        $delegatingLoader->load('{services}_'.$env.'.{php,yaml,yml}', 'glob');
    }
}
