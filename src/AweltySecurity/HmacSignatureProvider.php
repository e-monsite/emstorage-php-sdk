<?php

namespace Emonsite\Emstorage\PhpSdk\AweltySecurity;

use Psr\Http\Message\RequestInterface;

/**
 * signe des request en hmac
 */
class HmacSignatureProvider implements SignatureProviderInterface
{
    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var
     */
    private $algo;

    /**
     * HmacAuthenticator constructor.
     * @param string $publicKey
     * @param string $privateKey
     * @param string $algo md5, sha1, ...
     */
    public function __construct($publicKey, $privateKey, $algo)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->algo = $algo;
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function sign(RequestInterface $request)
    {
        foreach ($this->getHeaders($request->getMethod(), $request->getRequestTarget()) as $k => $v) {
            $request = $request->withHeader($k, $v);
        }

        return $request;
    }

    /**
     * Les headers pour $_SERVER (préfixé par HTTP_)
     */
    public function getServerHeaders($method, $requestTarget)
    {
        $headers = [];

        foreach ($this->getHeaders($method, $requestTarget) as $k => $v) {
            $headers['HTTP_'.$k] = $v;
        }

        return $headers;
    }

    /**
     * Pour signer autre chose qu'une requestInterface..
     */
    public function getHeaders($method, $requestTarget)
    {
        $now = new \DateTime();
        $plainSignature = $method.urldecode($requestTarget).$now->format(\DateTime::ISO8601);

        return [
            'X-Public-Key' => $this->publicKey,
            'X-Datetime' => $now->format(\DateTime::ISO8601),
            'X-Signature' => hash_hmac($this->algo, $plainSignature, $this->privateKey),
        ];
    }
}
