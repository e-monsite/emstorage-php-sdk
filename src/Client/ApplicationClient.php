<?php

namespace Emonsite\Emstorage\PhpSdk\Client;

class ApplicationClient extends AbstractClient
{
    /**
     * @return array
     */
    public function getApplication()
    {
        $jsonResponse = $this->client->request('GET', '/');

        return $this->serializer->decode($jsonResponse->getBody(), 'json')['application'];
        // return $this->serializer->deserialize($jsonResponse->getBody(), Application::class, 'json');
    }
}
