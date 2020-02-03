<?php

namespace Emonsite\Emstorage\PhpSdk\Client;

use Emonsite\Emstorage\PhpSdk\Model\Application;

class ApplicationClient extends AbstractClient
{
    public function getApplication(): Application
    {
        $jsonResponse = $this->request('GET', '/');

        return $this->serializer->deserialize($jsonResponse->getContent(), Application::class, 'json');
    }

    public function updateApplication(Application $application): self
    {
        $this->putJson('/', [
            'json' => $this->serializer->normalize($application)
        ]);

        return $this;
    }
}
