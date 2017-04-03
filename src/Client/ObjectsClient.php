<?php

namespace Emonsite\Emstorage\PhpSdk\Client;

use Emonsite\Emstorage\PhpSdk\Exception\ResponseException;
use Emonsite\Emstorage\PhpSdk\Model\Collection;
use Emonsite\Emstorage\PhpSdk\Model\EmObject;
use Emonsite\Emstorage\PhpSdk\Model\ObjectSummaryInterface;
use Emonsite\Emstorage\PhpSdk\Normalizer\CollectionNormalizer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Mimey\MimeTypes;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\Serializer;

class ObjectsClient extends AbstractClient
{

    /**
     * @var string
     */
    private $containerId;
    /**
     * @var MimeTypes
     */
    private $mimeTypes;

    public function __construct(Client $client, Serializer $serializer, MimeTypes $mimeTypes, $containerId)
    {
        parent::__construct($client, $serializer);
        $this->containerId = $containerId;
        $this->mimeTypes = $mimeTypes;
    }

//    /**
//     * Créer un objet vide sur le cloud
//     * @param ObjectSummaryInterface $object
//     * @return ObjectSummaryInterface
//     * @throws ResponseException
//     */
//    public function createFromObject(ObjectSummaryInterface $object)
//    {
//        // créer le fichier
//        try {
//            $response = $this->client->post('/objects', [
//                'json' => $this->serializer->normalize($object),
//            ]);
//        } catch (RequestException $e) {
//            throw new ResponseException($e);
//        }
//
//        return $this->serializer->deserialize($response->getBody(), EmObject::class, 'json');
//    }

//    /**
//     * Remplie un objet existant dans le cloud
//     * @param ObjectSummaryInterface $objectSummary
//     * @param string | resource | StreamInterface $content
//     * @return ObjectSummaryInterface
//     * @throws ResponseException
//     */
//    public function writeInObject(ObjectSummaryInterface $objectSummary, $content)
//    {
//        try {
//            $response = $this->client->post('/objects/'.$objectSummary->getId().'/bytes', [
//                'body' => $content,
//                'headers' => [
//                    'Content-Type' => 'application/octet-stream',
//                ]
//            ]);
//        } catch (RequestException $e) {
//            throw new ResponseException($e);
//        }
//
//        return $this->serializer->deserialize($response->getBody(), EmObject::class, 'json');
//    }

    /**
     * Créer le ficher avec son contenu dans le cloud
     * @param string $path
     * @param string | resource | StreamInterface $content
     * @return mixed
     * @throws ResponseException
     */
    public function create($path, $content)
    {
        // créate
        try {
            $response = $this->client->post('/objects/'.$this->containerId, [
                'json' => ['filename' => $path]
            ]);

            $object = $this->serializer->decode($response->getBody(), 'json')['object'];
        } catch (RequestException $e) {
            throw new ResponseException($e);
        }

        // upload
        try {
            $response = $this->client->post('/objects/' . $this->containerId . '/' . $object['id'] . '/bytes', [
                'body' => $content,
                'headers' => [
                    'Content-Type' => $this->getContentType($path),
                ]
            ]);
        } catch (RequestException $e) {
            $this->delete($path);
            throw new ResponseException($e);
        }

        return $this->serializer->decode($response->getBody(), 'json')['object'];
    }

    /**
     * Faux update, TODO en faire un vrai (qui ne change pas l'id de l'object ?)
     * @param $path
     * @param $content
     * @return array
     */
    public function update($path, $content)
    {
        $this->delete($path);
        return $this->create($path, $content);
    }

//    /**
//     * @param int $offset
//     * @param int $limit
//     * @return Collection|ObjectSummaryInterface[]
//     * @throws ResponseException
//     */
//    public function getObjects($offset = 0, $limit = 5)
//    {
//        try {
//            $response = $this->client->get('/objects', [
//                'query' => [
//                    'offset' => $offset,
//                    'limit' => $limit,
//                ],
//            ]);
//        } catch (RequestException $e) {
//            throw new ResponseException($e);
//        }
//
//        return $this->serializer->deserialize($response->getBody(), EmObject::class.'[]', 'json', [CollectionNormalizer::ELEMENTS_KEY => 'objects']);
//    }

    /**
     * @param $path
     * @throws ResponseException
     */
    public function delete($path)
    {
        $object = $this->find($path);

        if ($object) {
            try {
                $this->client->request('DELETE', '/objects/'.$this->containerId.'/'.$object['id']);
            } catch (RequestException $e) {
                throw new ResponseException($e);
            }
        }
    }
//
//    /**
//     * @param ObjectSummaryInterface $objectSummary
//     * @return \Psr\Http\Message\ResponseInterface
//     * @throws ResponseException
//     */
//    public function deleteFromObject(ObjectSummaryInterface $objectSummary)
//    {
//        try {
//            $this->client->delete('/objects/'.$objectSummary->getId());
//        } catch (RequestException $e) {
//            throw new ResponseException($e);
//        }
//    }
//
//    /**
//     * @param $id
//     * @return ObjectSummaryInterface
//     * @throws ResponseException
//     */
//    public function getObjectById($id)
//    {
//        try {
//            $response = $this->client->get('/objects/'.$id);
//        } catch (RequestException $e) {
//            throw new ResponseException($e);
//        }
//
//        return $this->serializer->deserialize($response->getBody(), EmObject::class, 'json');
//    }
//
//    /**
//     * @param string $path
//     * @return bool
//     * @throws ResponseException
//     */
//    public function hasObject($path)
//    {
//        try {
//            $response = $this->client->get('/objects', [
//                'query' => [
//                    'filter' => 'filename:eq:'.$path
//                ]
//            ]);
//
//            $response = \GuzzleHttp\json_decode($response->getBody()->getContents());
//            return (bool)$response->objects;
//        } catch (RequestException $e) {
//            throw new ResponseException($e);
//        }
//    }
//
//    /**
//     * @param string $path
//     * @return ObjectSummaryInterface
//     * @throws ResponseException
//     */
//    public function getObject($path)
//    {
//        try {
//            $response = $this->client->get('/objects', [
//                'query' => [
//                    'filter' => 'filename:eq:'.$path
//                ]
//            ]);
//
//        } catch (RequestException $e) {
//            throw new ResponseException($e);
//        }
//
//        /** @var Collection $objects */
//        $objects = $this->serializer->deserialize($response->getBody(), EmObject::class.'[]', 'json', [CollectionNormalizer::ELEMENTS_KEY => 'objects']);
//
//        if (count($objects) != 1) {
//            throw new \LogicException(sprintf('Unexpected result, 1 object expected, %s received', count($objects)));
//        }
//
//        return $objects[0];
//    }

    /**
     * @param array $criteria
     * @return array
     * @throws ResponseException
     */
    public function findBy(array $criteria /*, array $orderBy = null, $limit = null, $offset = null*/)
    {
        try {

            $jsonResponse = $this->client->request('GET', '/objects/'.$this->containerId, [
                'query' => $criteria,
            ]);
        } catch (RequestException $e) {
            throw new ResponseException($e);
        }

        $response = $this->serializer->decode($jsonResponse->getBody(), 'json');
        return $response['objects'];
    }

    public function findOneBy(array $criteria /*, array $orderBy = null*/)
    {
        $response = $this->findBy($criteria);

        return isset($response[0]) ? $response[0] : null;
    }

    public function find($path)
    {
        return $this->findOneBy([
            'filename' => $path,
        ]);
    }

    public function has($path)
    {
        return (bool)$this->find($path);
    }

    /**
     * @param $path
     */
    private function getContentType($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if ($ext) {
            return $this->mimeTypes->getMimeType($ext) ?: 'application/octet-stream';
        }

        return 'application/octet-stream';
    }
}
