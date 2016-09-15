# EmStorage PHP SDK

PHP SDK for http://fr.emstorage.com

## Install

```
composer require emstorage/php-sdk:dev-master
```
A stable version will eventually be released one day...

### Create a client

#### With Silex

You can use the service provider: 

```
<?php 

use Emonsite\Emstorage\PhpSdk\Bridge as EmStorageServiceProvider;

$app->register(new EmStorageServiceProvider(), [
    'emstorage.applications' => [
        'yourAppName' => [
            'public_key' => 'appPublicKey',
            'private_key' => 'appPrivateKey',
        ]
    ],
    'guzzle.options' => [
        'debug' => false,
    ]
]);
```

For each applications it will create a service named "emstorage.*yourAppName*"

#### Or manually

```
<?php 

use Awelty\Component\Security\HmacSignatureProvider;
use Emonsite\Emstorage\PhpSdk\Client;

// Emstorage use hmac authentification with sha1 as algo
$signatureProvider = new HmacSignatureProvider($publicKey, $privateKey, 'sha1');

// create as many clients as you need (typically one per EmStorageApplication)
$emStorage = new Client($authenticator, $someGuzzleConfig = []);
```

### Usage

The client has one subclient per resource in EmStorage.
TODO: one doc file per resource

#### ObjectClient

- Create a file (it will throw an exception if the file already exist - TODO)
```php
/** @var ObjectSummaryInterface $file **/
$file = $emStorage->object->create($path, $content);
```

- Update a file (it will throw an exception if the file doesn't exist - TODO)
```php
/** @var ObjectSummaryInterface $file **/
$file = $emStorage->object->update($path, $content);
```

- Delete a file (it will throw an exception if the file doesn't exist - TODO)
```php
$emStorage->object->delete($path);
```

- Get a file metadatas
```php
/** @var ObjectSummaryInterface $file **/
$file = $emStorage->object->getObject($path);
```

- Get a list
```php
/** @var Collection|ObjectSummaryInterface[] $files **/
$files = $emStorage->object->getObjects($offset = 0, $limit = 5);
```

**You also can work with models (ObjectSummaryInterface)**

- Create a file
```php
$file = new EmObject();
$file->setFilename($path);
$remoteFile = $emStorage->object->createFromObject($file);
// warning: returned $remoteFile is not the same instance as $file, TODO ?
```

Feel free to exlore the client with your IDE to find some other methods to create, get or delete file from object or objectId.

### Models

**ObjectSummaryInterface**

This is the interface you work with when you send files.

```
<?php

interface ObjectSummaryInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @return string
     */
    public function getPublicUrl();

    /**
     * @return string
     */
    public function getMime();

    /**
     * @return float
     */
    public function getSize();

    /**
     * @return string
     */
    public function getSizeHuman();

    /**
     * @return array
     */
    public function getMeta();

}
```

**Collection**

A collection of ObjectSummaryInterface.

```php
class Collection implements \ArrayAccess, \Countable, \Iterator
{
}
```

It also provide *nav* and *links* properties:
 
```php
$files = $emStorage->object->getObjects();

print_r($files->getNav());

/*
Array
(
    [total] => 38
    [count] => 5
    [offset] => 0
    [limit] => 5
    // TODO need [totalPage] ?
)
*/

print_r($files->getLinks());

/*
Array
(
    [prev] => 
    [next] => /objects?offset=5&limit=5
    // TODO need [nextOffset] ?
)
*/
```

To be continued...