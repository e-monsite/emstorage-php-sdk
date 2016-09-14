<?php

namespace Emonsite\Emstorage\PhpSdk\Bridge;

use Awelty\Component\Security\HmacSignatureProvider;
use Emonsite\Emstorage\PhpSdk\Client;
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
        $container['guzzle.options'] = [];
    }

    public function boot(Application $app)
    {
        // "force" base_uri
        $options = $app['guzzle.options'];
        $options['base_uri'] = 'https://api.emstorage.com';

        $app['guzzle.options'] = $options;

        // create services
        foreach ($app['emstorage.applications'] as $name => $config) {
            $authenticator = new HmacSignatureProvider($config['public_key'], $config['private_key'], 'sha1');

            $app['emstorage.'.$name.'.client'] = function (Container $container) use ($authenticator) {
                return new Client($authenticator, $container['guzzle.options']);
            };
        }
    }
}
