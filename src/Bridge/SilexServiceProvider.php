<?php

namespace Emonsite\Emstorage\PhpSdk\Bridge;

use Emonsite\Emstorage\PhpSdk\AweltySecurity\HmacSignatureProvider;
use Emonsite\Emstorage\PhpSdk\Client;
use Emonsite\Emstorage\PhpSdk\Emstorage;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

class SilexServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $container)
    {
        /**
         * Les applications EmStorage
         */
        $container['emstorage.applications'] = [];

        /**
         * Options pour construire le client Guzzle
         */
        $container['emstorage.guzzle.options'] = [];
    }

    public function boot(Application $app)
    {
        // create services
        foreach ($app['emstorage.applications'] as $name => $config) {
            $app['emstorage.'.$name] = function (Container $container) use ($config) {
                return new Emstorage($config['public_key'], $config['private_key']);
            };
        }
    }
}
