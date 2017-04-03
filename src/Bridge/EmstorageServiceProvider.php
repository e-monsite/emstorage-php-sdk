<?php

namespace Emonsite\Emstorage\PhpSdk\Bridge;

use Emonsite\Emstorage\PhpSdk\Emstorage;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

/**
 * Silex (pimple) provider
 */
class EmstorageServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $container)
    {
        /**
         * Les applications EmStorage
         */
        $container['emstorage.applications'] = [];
    }

    public function boot(Application $app)
    {
        // create services
        foreach ($app['emstorage.applications'] as $name => $config) {
            if (!isset($config['options'])) {
                $config['options'] = [];
            }

            $app['emstorage.'.$name] = function (Container $container) use ($config) {
                return new Emstorage($config['public_key'], $config['private_key'], $config['options']);
            };
        }
    }
}
