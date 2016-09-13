<?php

namespace Emonsite\Emstorage\PhpSdk\Exception;

use GuzzleHttp\Exception\RequestException;

/**
 * Une erreur côté serveur emStorage
 */
class ResponseException extends EmStorageException
{
    /**
     * La réponse Json as array
     * @var array|null
     */
    private $response;
    
    public function __construct(\Exception $previous)
    {
        $message = 'Unknown error';
        $code = 0;

        // the api response
        $response = null;

        if ($previous instanceof RequestException) {
            $response = $previous->getResponse();
        }

        // TODO autre if instanceof ?


        if ($response) {
            $json = $response->getBody()->getContents();
            
            if ($json) {
                $this->response = json_decode($json, true);
                $message = $this->response['code'];
            }
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
