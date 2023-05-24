<?php

namespace Emonsite\Emstorage\PhpSdk\AweltySecurity;

use Psr\Http\Message\RequestInterface;

/**
 * signe des request avec un header
 * TODO append or not
 * TODO ChainHeaderSignatureProvider
 */
class HeaderSignatureProvider implements SignatureProviderInterface
{
    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function sign(RequestInterface $request)
    {
        return $request->withHeader($this->name, $this->value);
    }
}
