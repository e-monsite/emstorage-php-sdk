<?php

declare(strict_types=1);

namespace Emonsite\Emstorage\PhpSdk\Tests;

use Emonsite\Emstorage\PhpSdk\Emstorage;
use Emonsite\Emstorage\PhpSdk\Model\Collection;
use Emonsite\Emstorage\PhpSdk\Model\EmObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class EmstorageTest extends TestCase
{
    /**
     * Les fichiers temporaires qui seront unlink à la fin des tests (voir tearDown())
     */
    private $temporaryFilesPaths = [];

    public function testCreateObject()
    {
        $mockedResponses = function (string $method, string $url, array $options) {
            static $i = -1;
            static $filename = null;
            static $mime = null;
            $i++;

            switch ($i) {
                case 0:
                    $body = json_decode($options['body'], true);
                    $filename = $body['filename'];
                    $mime = $body['mime'];

                    return new MockResponse(
                        json_encode([
                            'success' => true,
                            'object' => [
                                'id' => uniqid(),
                                'createdAt' => (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.v\Z'),
                                'filename' => $filename,
                                'public_url' => 'blabla.emstorage.fr/'.$filename,
                                'mime' => $mime,
                                'size' => 0,
                                'size_human' => '0 B',
                                'has_custom_data' => false,
                                'has_filters' => false,
                                'custom_data' => [],
                                'filters' => [],
                            ],
                        ])
                    );

                case 1:
                    return new MockResponse(
                        json_encode([
                            'success' => true,
                            'object' => [
                                'id' => uniqid(),
                                'createdAt' => (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.v\Z'),
                                'filename' => $filename,
                                'public_url' => 'blabla.emstorage.fr/'.$filename,
                                'mime' => $mime,
                                'size' => fstat($options['body'])['size'],
                                'size_human' => 'osef',
                                'has_custom_data' => false,
                                'has_filters' => false,
                                'custom_data' => [],
                                'filters' => [],
                            ],
                        ])
                    );

                default:
                    throw new \Exception('Too much requests');
            }
        };

        $emstorage = $this->getEmstorage($mockedResponses);
        $emObject = $emstorage->objects('123')->create('coucou.txt', $this->getTmpFilePath('hello'));

        static::assertSame('coucou.txt', $emObject->getFilename());
        static::assertSame('text/plain', $emObject->getMime());
        static::assertInstanceOf(\DateTimeInterface::class, $emObject->getCreatedAt());
        static::assertSame(5, $emObject->getSize());
    }

    public function testGetObjects(): void
    {
        $emstorage = $this->getEmstorage(new MockResponse(file_get_contents(__DIR__.'/ResponseSamples/getObjects.json')));
        $objects = $emstorage->objects('123')->getObjects();

        static::assertInstanceOf(Collection::class, $objects);
        static::assertInstanceOf(EmObject::class, $objects[0]);
    }

    public function testHasObject(): void
    {
        $mockedResponses = function (string $method, string $url, array $options) {
            // fichier existe
            if ($options['query']['filename'] === 'coucou.txt') {
                return new MockResponse('{"success":true,"objects":[{"id":"5e31b8eceb7b7822da56462f","createdAt":"2020-01-29T16:55:08.972Z","filename":"coucou.txt","public_url":"emsdev-zobzob.emstorage.fr/coucou.txt","mime":null,"size":0,"size_human":"0 B","has_custom_data":false,"has_filters":false,"custom_data":{},"links":[{"rel":"self","href":"https://api.emstorage.fr/objects/5e3197152c38a522dc138aee/5e31b8eceb7b7822da56462f"}]}],"nav":{"query_string":"filename=coucou.txt","sort":"_id","count":1,"offset":0,"limit":5},"links":[{"rel":"self","href":"https://api.emstorage.fr/objects/5e3197152c38a522dc138aee"}]}');
            }

            // fichier existe pas
            return new MockResponse('{"success":true,"objects":[],"nav":{"query_string":"filename=unexistant-file.txt","sort":"_id","count":0,"offset":0,"limit":5},"links":[{"rel":"self","href":"https://api.emstorage.fr/objects/5e3197152c38a522dc138aee"}]}');
        };

        $emstorage = $this->getEmstorage($mockedResponses);

        static::assertTrue($emstorage->objects('123')->hasObject('coucou.txt'));
        static::assertFalse($emstorage->objects('123')->hasObject((string)rand()));
    }

    public function tearDown(): void
    {
        array_map('unlink', $this->temporaryFilesPaths);
    }

    /**
     * return le path d'un fichier temporaire qui se supprimera auto
     */
    private function getTmpFilePath(string $content): string
    {
        $path = tempnam(sys_get_temp_dir(), (string)rand());
        file_put_contents($path, $content);

        return $path;
    }

    /**
     * @para callable|ResponseInterface|ResponseInterface[]|iterable|null $mockedResponses
     */
    private function getEmstorage($mockedResponses): Emstorage
    {
        return new Emstorage('4f4109dbe4b1bcfd9ab9e32e', 'cbcb5516247fdd5ea3ed911c', new MockHttpClient($mockedResponses, Emstorage::BASE_URI));
    }


}
