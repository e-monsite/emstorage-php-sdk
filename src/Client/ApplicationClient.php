<?php

namespace Emonsite\Emstorage\PhpSdk\Client;

use Emonsite\Emstorage\PhpSdk\Model\Application;

class ApplicationClient extends AbstractClient
{
    /**
     * @return Application
     */
    public function getApplication()
    {
        $jsonResponse = $this->client->request('GET', '/');

        return $this->serializer->deserialize($jsonResponse->getBody(), Application::class, 'json');
    }

    /**
     * @param Application $application
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateApplication(Application $application)
    {
        return $this->client->put('/', [
            'json' => $this->serializer->normalize($application)
        ]);
    }
}
