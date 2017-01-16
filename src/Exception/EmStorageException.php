<?php

namespace Emonsite\Emstorage\PhpSdk\Exception;

use Exception;

/**
 * Une exception quelque part dans l'api
 * Toutes les autres exceptions spécifique à une action d'API doivent extend celle là
 */
class EmStorageException extends Exception
{
}
