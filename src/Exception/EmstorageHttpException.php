<?php

namespace Emonsite\Emstorage\PhpSdk\Exception;


use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Une erreur côté serveur emStorage
 */
class EmstorageHttpException extends EmStorageException
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(HttpExceptionInterface $httpException)
    {
        $this->response = $httpException->getResponse();

        parent::__construct($this->findRemoteError() ?: $httpException->getMessage(), $this->findRemoteErrorCode() ?: $httpException->getCode(), $httpException);
    }

    public function getHttpResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getResponseContent(): string
    {
        return $this->response->getContent(false);
    }

    public function getResponseHttpCode(): int
    {
        return $this->response->getInfo('http_code');
    }

    public function toArray(): array
    {
        return $this->response->toArray(false);
    }

    public function findRemoteError(): ?string
    {
        return $this->toArray()['code'] ?? null;
    }

    public function findRemoteErrorCode(): ?int
    {
        return $this->toArray()['http_code'] ?? null;
    }
}
