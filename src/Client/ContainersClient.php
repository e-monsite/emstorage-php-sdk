<?php

namespace Emonsite\Emstorage\PhpSdk\Client;

class ContainersClient extends AbstractClient
{
    public function all()
    {
        $jsonResponse = $this->client->request('GET', '/containers');

        return $this->serializer->decode($jsonResponse->getBody(), 'json');
    }

    public function findBy(array $criteria /*, array $orderBy = null, $limit = null, $offset = null*/)
    {
        $jsonResponse = $this->client->request('GET', '/containers', [
            'query' => $criteria,
        ]);

        $response = $this->serializer->decode($jsonResponse->getBody(), 'json');

        return $response['containers'];
    }

    public function findOneBy(array $criteria /*, array $orderBy = null*/)
    {
        $response = $this->findBy($criteria);

        return isset($response[0]) ? $response[0] : null;
    }
}
