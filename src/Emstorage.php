<?php

declare(strict_types=1);

namespace Emonsite\Emstorage\PhpSdk;

use Awelty\Component\Security\HmacSignatureProvider;
use Emonsite\Emstorage\PhpSdk\Client\ApplicationClient;
use Emonsite\Emstorage\PhpSdk\Client\ObjectClient;
use Emonsite\Emstorage\PhpSdk\Normalizer\ApplicationNormalizer;
use Emonsite\Emstorage\PhpSdk\Normalizer\CollectionNormalizer;
use Emonsite\Emstorage\PhpSdk\Normalizer\EmObjectNormalizer;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Emstorage
{
    public const BASE_URI = 'https://api.emstorage.fr';

    private $applicationClient;

    public $objectClient;

    private $signatureProvider;

    private $httpClient;

    private $serializer;

    /**
     * if provided, $httpClient must hold the base uri
     */
    public function __construct(string $publicKey, string $privateKey, HttpClientInterface $httpClient = null)
    {
        $this->signatureProvider = new HmacSignatureProvider($publicKey, $privateKey, 'sha1');
        $this->httpClient = $httpClient ?: HttpClient::createForBaseUri(static::BASE_URI);
        $this->serializer = $this->createSerializer();
    }

    public function application(): ApplicationClient
    {
        return $this->applicationClient ?: $this->applicationClient = new ApplicationClient($this->httpClient, $this->serializer, $this->signatureProvider);
    }

    public function objects(string $containerId): ObjectClient
    {
        return $this->objectClient && $this->objectClient->getContainerId() === $containerId
            ? $this->objectClient
            : $this->objectClient = new ObjectClient($this->httpClient, $this->serializer, $this->signatureProvider, $containerId);
    }

    private function createSerializer(): Serializer
    {
        $camelCaseToSnakeCaseNameConverter = new CamelCaseToSnakeCaseNameConverter();
        // TODO propertInfoExtractor ?

        $normalizers = [
            new EmObjectNormalizer(null, $camelCaseToSnakeCaseNameConverter),
            new ApplicationNormalizer(null, $camelCaseToSnakeCaseNameConverter),
            new ObjectNormalizer(null, $camelCaseToSnakeCaseNameConverter),
            new CollectionNormalizer(),
        ];

        return new Serializer($normalizers, [new JsonEncoder()]);
    }
}
