<?php

namespace Emonsite\Emstorage\PhpSdk\Client;

use Awelty\Component\Security\HmacSignatureProvider;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AbstractClient
{
    private $httpClient;

    private $signatureProvider;

    protected $serializer;

    public function __construct(HttpClientInterface $httpClient, Serializer $serializer, HmacSignatureProvider $signatureProvider)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->signatureProvider = $signatureProvider;
    }

    protected function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $uri, $this->auhtenticateOptions($method, $uri, $options));
    }

    protected function get(string $url, array $options = []): ResponseInterface
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * @param mixed $datas, will be json encoded
     */
    protected function putJson(string $url, $datas): ResponseInterface
    {
        return $this->request('PUT', $url, ['json' => $datas]);
    }

    /**
     * @param array|string|resource|\Traversable|\Closure $body
     */
    protected function post(string $url, $body): ResponseInterface
    {
        return $this->request('POST', $url, ['body' => $body]);
    }

    /**
     * @param mixed $datas, will be json encoded
     */
    protected function postJson(string $url, $datas): ResponseInterface
    {
        return $this->request('POST', $url, ['json' => $datas]);
    }

    protected function delete(string $url): ResponseInterface
    {
        return $this->request('DELETE', $url);
    }

    /**
     * Signe en hmac en ajoutant les headers qu'il faut
     */
    private function auhtenticateOptions(string $method, string $url, array $options = []): array
    {
        $headers = $options['headers'] ?? [];

        if (!is_array($headers)) {
            $headers = iterator_to_array($headers);
        }

        if (isset($options['query'])) {
            $url = $url.'?'.http_build_query($options['query']);
        }

        $options['headers'] = array_merge($headers, $this->signatureProvider->getHeaders($method, $url));

        return $options;
    }
}
