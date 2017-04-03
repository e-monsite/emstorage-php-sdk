<?php

namespace Emonsite\Emstorage\PhpSdk\Bridge;

use Emonsite\Emstorage\PhpSdk\Client\ObjectsClient;
use Emonsite\Emstorage\PhpSdk\Exception\EmStorageException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;
use Psr\Http\Message\StreamInterface;

/**
 * TODO throw AdapterException quand elle existera..
 * https://github.com/thephpleague/flysystem/issues/620
 */
class FlysystemAdapter implements AdapterInterface
{
    /**
     * @var ObjectsClient
     */
    private $client;

    public function __construct(ObjectsClient $client)
    {
        $this->client = $client;
    }


    /**
     * Write a new file.
     *
     * @param string $path
     * @param string | resource | StreamInterface $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
    {
        try {
            $object = $this->client->create($path, $contents);
            return ['path' => $path];
        } catch (EmStorageException $e) {
            throw $e;
            return false;
        }
    }

    /**
     * Write a new file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
        return $this->write($path, $resource, $config);
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        try {
            $object = $this->client->update($path, $contents);
            return ['path' => $path];
        } catch (EmStorageException $e) {
            throw $e;
            return false;
        }
    }

    /**
     * Update a file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function updateStream($path, $resource, Config $config)
    {
        return $this->update($path, $resource, $config);
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function rename($path, $newpath)
    {
        // TODO ?
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($path, $newpath)
    {
        // TODO ?
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
        try {
            $this->client->delete($path);
            return true;
        } catch (EmStorageException $e) {
            throw $e;
            return false;
        }
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($dirname)
    {
        // TODO: Implement deleteDir() method.
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     *
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
        // TODO: Implement createDir() method.
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
    {
        // TODO: Implement setVisibility() method.
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return array|bool|null
     */
    public function has($path)
    {
        return $this->client->has($path);
    }

    /**
     * Read a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function read($path)
    {
        // TODO: Implement read() method.
    }

    /**
     * Read a file as a stream.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function readStream($path)
    {
        // TODO: Implement readStream() method.
    }

    /**
     * List contents of a directory.
     *
     * @param string $directory
     * @param bool $recursive
     *
     * @return array
     */
    public function listContents($directory = '', $recursive = false)
    {
        // TODO: Implement listContents() method.
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMetadata($path)
    {
        // TODO: Implement getMetadata() method.
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getSize($path)
    {
        // TODO: Implement getSize() method.
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMimetype($path)
    {
        // TODO: Implement getMimetype() method.
    }

    /**
     * Get the timestamp of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getTimestamp($path)
    {
        // TODO: Implement getTimestamp() method.
    }

    /**
     * Get the visibility of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
    }
}
