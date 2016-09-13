<?php

namespace Emonsite\Emstorage\PhpSdk\Exception;

use Exception;

/**
 * Une exception quelque part dans l'api
 * Toutes les autres exceptions spécifique à une action d'API doivent extend celle là
 */
class EmStorageException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
