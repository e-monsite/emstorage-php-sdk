<?php

declare(strict_types=1);

namespace Emonsite\Emstorage\PhpSdk\Client;

use Awelty\Component\Security\HmacSignatureProvider;
use Emonsite\Emstorage\PhpSdk\Exception\EmstorageHttpException;
use Emonsite\Emstorage\PhpSdk\Model\Collection;
use Emonsite\Emstorage\PhpSdk\Model\EmObject;
use Emonsite\Emstorage\PhpSdk\Model\ObjectSummaryInterface;
use Emonsite\Emstorage\PhpSdk\Normalizer\CollectionNormalizer;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ObjectClient extends AbstractClient
{
    private $containerId;

    public function __construct(HttpClientInterface $httpClient, Serializer $serializer, HmacSignatureProvider $signatureProvider, string $containerId)
    {
        parent::__construct($httpClient, $serializer, $signatureProvider);

        $this->containerId = $containerId;
    }

    public function getContainerId(): string
    {
        return $this->containerId;
    }

    /**
     * Créer le ficher avec son contenu dans le cloud
     * Envoyer $mimeType pour le forcer (et optimiser)
     */
    public function create(string $remotePath, string $localPath, string $mimeType = null): ObjectSummaryInterface
    {
        return $this->createStream($remotePath, fopen($localPath, 'r'), $this->guessMimeType($localPath));
    }

    /**
     * Créer le ficher avec son contenu dans le cloud
     * Envoyer $mimeType pour le forcer (et optimiser)
     */
    public function createStream(string $remotePath, $resource, string $mimeType = null): ObjectSummaryInterface
    {
        // créer l'objet vide
        $response = $this->postJson('/objects/'.$this->containerId, [
            'filename' => $remotePath,
            'mime' => $mimeType ?: $this->guessMimeType($remotePath),
        ]);

        try {
            $response->getContent();
        } catch (HttpExceptionInterface $e) {
            throw new EmstorageHttpException($e);
        }

        // post les bytes
        $response = $this->post('/objects/'.$this->containerId.'/'.$response->toArray()['object']['id'].'/bytes', $resource);

        try {
            return $this->serializer->deserialize($response->getContent(), EmObject::class, 'json');
        } catch (HttpExceptionInterface $e) {
            throw new EmstorageHttpException($e);
        }
    }

    /**
     * Update le contenu d'un fichier qui existe déjà
     */
    public function update(string $remotePath, string $localPath): ObjectSummaryInterface
    {
        return $this->updateStream($remotePath, fopen($localPath, 'r'));
    }

    public function updateStream(string $remotePath, $resource): ObjectSummaryInterface
    {
        $this->deleteFromPath($remotePath);

        return $this->createStream($remotePath, $resource);
    }

    /**
     * @return Collection|ObjectSummaryInterface[]
     */
    public function getObjects(int $offset = 0, int $limit = 5): Collection
    {
        $response = $this->get('/objects/'.$this->containerId, [
            'query' => [
                'offset' => $offset,
                'limit' => $limit,
            ],
        ]);

        try {
            return $this->serializer->deserialize($response->getContent(), EmObject::class.'[]', 'json', [CollectionNormalizer::ELEMENTS_KEY => 'objects']);
        } catch (HttpExceptionInterface $e) {
            throw new EmstorageHttpException($e);
        }
    }

    public function deleteFromPath(string $remotePath): void
    {
        $object = $this->getObject($remotePath);

        $this->deleteFromObject($object);
    }

    public function deleteFromObject(ObjectSummaryInterface $objectSummary): void
    {
        $this->delete('/objects/'.$this->containerId.'/'.$objectSummary->getId());
    }

    public function getObjectById(string $id): ObjectSummaryInterface
    {
        $response = $this->get('/objects/'.$this->containerId.'/'.$id);

        try {
            return $this->serializer->deserialize($response->getContent(), EmObject::class, 'json');
        } catch (HttpExceptionInterface $e) {
            throw new EmstorageHttpException($e);
        }
    }

    public function hasObject(string $remotePath): bool
    {
        $response = $this->get('/objects/'.$this->containerId, [
            'query' => [
                'filename' => $remotePath
            ]
        ]);

        try {
            $response = $response->toArray();
        } catch (HttpExceptionInterface $e) {
            throw new EmstorageHttpException($e);
        }

        return (bool) $response['objects'];
    }

    public function getObject(string $path): EmObject
    {
        $response = $this->get('/objects/'.$this->containerId, [
            'query' => [
                'filename' => $path
            ]
        ]);

        try {
            /** @var Collection $objects */
            $objects = $this->serializer->deserialize($response->getContent(), EmObject::class.'[]', 'json', [CollectionNormalizer::ELEMENTS_KEY => 'objects']);
        } catch (HttpExceptionInterface $e) {
            throw new EmstorageHttpException($e);
        }

        if (count($objects) !== 1) {
            throw new \LogicException(sprintf('Unexpected result, 1 object expected, %s received', count($objects)));
        }

        return $objects[0];
    }

    /**
     * Le mime type
     */
    private function guessMimeType($path): string
    {
        // mode "propre"
        if (is_readable($path)) {
            $mime = MimeTypes::getDefault()->guessMimeType($path);

            if ($mime) {
                return $mime;
            }
        }

        // fallback sur du sale : selon l'extension
        $ext = pathinfo($path, \PATHINFO_EXTENSION);

        if ($ext) {
            return MimeTypes::getDefault()->getMimeTypes($ext)[0] ?? 'application/octet-stream';
        }

        return 'application/octet-stream';
    }
}
