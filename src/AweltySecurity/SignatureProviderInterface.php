<?php

namespace Emonsite\Emstorage\PhpSdk\AweltySecurity;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

interface SignatureProviderInterface
{
    /**
     * Sign a request to authenticate
     * @param RequestInterface $request
     * @return Request
     */
    public function sign(RequestInterface $request);
}
