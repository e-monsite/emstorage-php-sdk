<?php

namespace Emonsite\Emstorage\PhpSdk\Bridge;

use Emonsite\Emstorage\PhpSdk\Client\ObjectClient;
use Emonsite\Emstorage\PhpSdk\Emstorage;
use Emonsite\Emstorage\PhpSdk\Exception\EmStorageException;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
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

    public function fileExists(string $path): bool
    {
        throw new \Exception('implement copy (or move) in Emstorage client !');
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

    public function move(string $source, string $destination, Config $config): void
    {
        // TODO: Implement move() method.
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy(string $source, string $destination, Config $config): void
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
    public function delete(string $path): void
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
    public function deleteDirectory(string $dirname): void
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
    public function createDirectory(string $dirname, Config $config)
    {
        throw new \Exception('createDir is not supported by emstorage');
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     */
    public function setVisibility(string $path, string $visibility): void
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
    public function read(string $path): string
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
    public function readStream(string $path)
    {
        throw new \Exception('Implement readStream in emstorage client !');
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     */
    public function listContents(string $path, bool $deep): iterable
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
     */
    public function getMimetype(string $path)
    {
        // TODO: Implement getMimetype() method.
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     */
    public function mimeType(string $path)
    {
        // TODO: Implement mimeType() method.
    }

    public function lastModified(string $path): FileAttributes
    {
        // TODO: Implement lastModified() method.
    }

    public function fileSize(string $path): FileAttributes
    {
        // TODO: Implement fileSize() method.
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

    public function visibility(string $path): FileAttributes
    {
        throw new \Exception('getVisibility is not supported by emstorage');
    }

    /**
     * Get the visibility of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getVisibility(string $path)
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
