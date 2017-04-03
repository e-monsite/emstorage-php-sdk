<?php

namespace Emonsite\Emstorage\PhpSdk;

use Awelty\Component\Security\HmacSignatureProvider;
use Awelty\Component\Security\MiddlewareProvider;
use Emonsite\Emstorage\PhpSdk\Client\ApplicationClient;
use Emonsite\Emstorage\PhpSdk\Client\ContainersClient;
use Emonsite\Emstorage\PhpSdk\Client\ObjectsClient;
use Emonsite\Emstorage\PhpSdk\Normalizer\ApplicationNormalizer;
use Emonsite\Emstorage\PhpSdk\Normalizer\CollectionNormalizer;
use Emonsite\Emstorage\PhpSdk\Normalizer\ObjectNormalizer;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as HttpClient;
use Mimey\MimeTypes;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as DefaultObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Emstorage
{
    const BASE_URI = 'https://api.emstorage.com';

    /**
     * @var ApplicationClient|null
     */
    private $application;

    /**
     * @var ObjectsClient[]
     */
    private $objects = [];

    /**
     * @var ContainersClient
     */
    private $containers;

    public function __construct($publicKey, $privateKey, $guzzleOptions = [])
    {
        // Handler
        //---------
        $hmacSignatureProvider = new HmacSignatureProvider($publicKey, $privateKey, 'sha1');
        $handler = !empty($guzzleOptions['handler']) ? $guzzleOptions['handler'] : HandlerStack::create();
        $handler->push(MiddlewareProvider::signRequestMiddleware($hmacSignatureProvider));

        $guzzleOptions['handler'] = $handler;

        // set a base_uri if not provided
        //--------------------------------
        if (empty($guzzleOptions['base_uri'])) {
            $guzzleOptions['base_uri'] = self::BASE_URI;
        }

        $this->client = new HttpClient($guzzleOptions);
    }

    public function application()
    {
        $serializer = new Serializer([], [new JsonEncoder()]);

        return $this->application ?: $this->application = new ApplicationClient($this->client, $serializer);
    }

    public function containers()
    {
        $serializer = new Serializer([], [new JsonEncoder()]);

        return $this->containers ?: $this->containers = new ContainersClient($this->client, $serializer);
    }

    /**
     * @param $containerName
     * @return ObjectsClient
     */
    public function objects($containerName)
    {
        if (!isset($this->objects[$containerName])) {
            $container = $this->containers()->findOneBy(['name' => $containerName]);

            if (!$container) {
                throw new \InvalidArgumentException(sprintf('Container "%s" not found', $containerName));
            }

            $serializer = new Serializer([], [new JsonEncoder()]);

            $this->objects[$containerName] = new ObjectsClient($this->client, $serializer, new MimeTypes(), $container['id']);
        }

        return $this->objects[$containerName];
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
