<?php

namespace Emonsite\Emstorage\PhpSdk;

use Awelty\Component\Security\HmacSignatureProvider;
use Awelty\Component\Security\MiddlewareProvider;
use Emonsite\Emstorage\PhpSdk\Client\ApplicationClient;
use Emonsite\Emstorage\PhpSdk\Client\ObjectClient;
use Emonsite\Emstorage\PhpSdk\Normalizer\ApplicationNormalizer;
use Emonsite\Emstorage\PhpSdk\Normalizer\CollectionNormalizer;
use Emonsite\Emstorage\PhpSdk\Normalizer\ObjectNormalizer;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as HttpClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as DefaultObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Client
{
    const BASE_URI = 'https://api.emstorage.com';

    /**
     * @var ApplicationClient
     */
    public $application;

    /**
     * @var ObjectClient
     */
    public $object;

    public function __construct(HmacSignatureProvider $hmacSignatureProvider, $guzzleOptions = [])
    {
        // Handler
        //---------
        $handler = !empty($guzzleOptions['handler']) ? $guzzleOptions['handler'] : HandlerStack::create();
        $handler->push(MiddlewareProvider::signRequestMiddleware($hmacSignatureProvider));

        $guzzleOptions['handler'] = $handler;

        // set a base_uri if not provided
        //--------------------------------
        if (empty($guzzleOptions['base_uri'])) {
            $guzzleOptions['base_uri'] = self::BASE_URI;
        }

        // Création des clients
        //----------------------
        $serializer = $this->createSerializer();

        $client = new HttpClient($guzzleOptions);

        $this->application = new ApplicationClient($client, $serializer);
        $this->object = new ObjectClient($client, $serializer);
    }

    /**
     * @return Serializer
     */
    private function createSerializer()
    {
        $encoders = [new JsonEncoder()];
        $camelCaseToSnakeCaseNameConverter = new CamelCaseToSnakeCaseNameConverter();
        // TODO propertInfoExtractor ?

        $normalizers = [
            new ObjectNormalizer(null, $camelCaseToSnakeCaseNameConverter),
            new ApplicationNormalizer(null, $camelCaseToSnakeCaseNameConverter),
            new DefaultObjectNormalizer(null, $camelCaseToSnakeCaseNameConverter),
            new CollectionNormalizer(),
        ];

        return new Serializer($normalizers, $encoders);
    }
}
