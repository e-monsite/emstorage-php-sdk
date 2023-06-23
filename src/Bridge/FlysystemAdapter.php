<?php

namespace Emonsite\Emstorage\PhpSdk\Bridge;

use Emonsite\Emstorage\PhpSdk\Client\ObjectClient;
use Emonsite\Emstorage\PhpSdk\Emstorage;
use Emonsite\Emstorage\PhpSdk\Exception\EmStorageException;
use League\Flysystem\Config;
use League\Flysystem\FilesystemAdapter;
use Psr\Http\Message\StreamInterface;

/**
 * TODO throw AdapterException quand elle existera..
 * https://github.com/thephpleague/flysystem/issues/620
 */
class FlysystemAdapter implements FilesystemAdapter
{
    /**
     * @var ObjectClient
     */
    private $objectClient;

    public function __construct(ObjectClient $objectClient)
    {
        $this->objectClient = $objectClient;
    }

    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config): void
    {
        $this->writeStream($path, $this->createStream($contents), $config);
    }

    /**
     * Write a new file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config): void
    {
        $object = $this->objectClient->createStream($path, $resource, $config->get('mime'));

        try {
            $object->getFilename();
        } catch (\Throwable $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        return $this->updateStream($path, $this->createStream($contents), $config);
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
        $object = $this->objectClient->updateStream($path, $resource);

        return ['path' => $object->getFilename()];
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function rename($path, $newpath): void
    {
        throw new \Exception('implement rename in Emstorage client !');
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($path, $newpath): void
    {
        throw new \Exception('implement copy (or move) in Emstorage client !');
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path): void
    {
        if ($this->has($path)) {
            $this->objectClient->deleteFromPath($path);
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
        throw new \Exception('deleteDir is not supported by emstorage');
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
        throw new \Exception('createDir is not supported by emstorage');
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility): void
    {
        throw new \Exception('setVisibility is not supported by emstorage');
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
        return $this->objectClient->hasObject($path);
    }

    /**
     * Read a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function read($path): string
    {
        throw new \Exception('Implement read in emstorage client !');
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
        throw new \Exception('Implement readStream in emstorage client !');
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function listContents($directory = '', $recursive = false): iterable
    {
        throw new \Exception('listContents is not supported by emstorage');
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
     * Get the size of a file.
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
        throw new \Exception('getVisibility is not supported by emstorage');
    }

    /**
     * @returnresource
     */
    private function createStream(string $content)
    {
        $stream = fopen('php://temp','r+');
        fwrite($stream, $content);
        rewind($stream);

        return $stream;
    }
}
