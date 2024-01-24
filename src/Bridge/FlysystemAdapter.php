<?php

namespace Emonsite\Emstorage\PhpSdk\Bridge;

use Emonsite\Emstorage\PhpSdk\Client\ObjectClient;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;

/**
 * TODO throw AdapterException quand elle existera..
 * https://github.com/thephpleague/flysystem/issues/620
 */
class FlysystemAdapter implements FilesystemAdapter
{
    private ObjectClient $objectClient;

    public function __construct(ObjectClient $objectClient)
    {
        $this->objectClient = $objectClient;
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $this->writeStream($path, $this->createStream($contents), $config);
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->objectClient->createStream($path, $contents, $config->get('mime'));
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        throw new \Exception('implement copy (or move) in Emstorage client !');
    }

    public function delete(string $path): void
    {
        if ($this->fileExists($path)) {
            $this->objectClient->deleteFromPath($path);
        }
    }

    public function setVisibility(string $path, string $visibility): void
    {
        throw new \Exception('setVisibility is not supported by emstorage');
    }

    public function read(string $path): string
    {
        throw new \Exception('Implement read in emstorage client !');
    }

    public function readStream(string $path)
    {
        throw new \Exception('Implement readStream in emstorage client !');
    }

    public function listContents(string $path, bool $deep): iterable
    {
        throw new \Exception('listContents is not supported by emstorage');
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

    public function fileExists(string $path): bool
    {
        return $this->objectClient->hasObject($path);
    }

    public function deleteDirectory(string $path): void
    {
        throw new \Exception('Implement it in emstorage client !');
    }

    public function createDirectory(string $path, Config $config): void
    {
        throw new \Exception('Implement it in emstorage client !');
    }

    public function visibility(string $path): FileAttributes
    {
        throw new \Exception('Implement it in emstorage client !');
    }

    public function mimeType(string $path): FileAttributes
    {
        throw new \Exception('Implement it in emstorage client !');
    }

    public function lastModified(string $path): FileAttributes
    {
        throw new \Exception('Implement it in emstorage client !');
    }

    public function fileSize(string $path): FileAttributes
    {
        throw new \Exception('Implement it in emstorage client !');
    }

    public function move(string $source, string $destination, Config $config): void
    {
        throw new \Exception('Implement it in emstorage client !');
    }
}
