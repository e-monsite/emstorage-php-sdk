<?php

namespace Emonsite\Emstorage\PhpSdk\Client;

use GuzzleHttp\Client;
use Symfony\Component\Serializer\Serializer;

class AbstractClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * ApplicationClient constructor.
     * @param Client $client
     * @param Serializer $serializer
     */
    public function __construct(Client $client, Serializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }
}
