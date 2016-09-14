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
        $serializer = $this->createSerializer();

        // Création du handler
        //---------------------
        $handler = HandlerStack::create();
        $handler->push(MiddlewareProvider::signRequestMiddleware($hmacSignatureProvider));

        if (isset($guzzleOptions['handler'])) {
            throw new \Exception('Do you really need to set an handler ?');
        }

        $guzzleOptions['handler'] = $handler;

        // Création des clients
        //----------------------
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
