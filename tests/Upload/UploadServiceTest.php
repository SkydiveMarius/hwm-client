<?php
namespace SkydiveMarius\HWM\Client\Tests\Upload;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SkydiveMarius\HWM\Client\Src\Distance\DistanceCollection;
use SkydiveMarius\HWM\Client\Src\Upload\UploadService;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;

/**
 * Class UploadServiceTest
 *
 * @package SkydiveMarius\HWM\Client\Tests\Upload
 */
class UploadServiceTest extends TestCase
{
    /**
     * @var Request[][]
     */
    protected $requestContainer = [];

    /**
     * @var MockHandler
     */
    protected $clientMockHandler;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var UploadService
     */
    private $service;

    protected function setUp()
    {
        $history = Middleware::history($this->requestContainer);
        $this->clientMockHandler = new MockHandler();

        $stack = HandlerStack::create($this->clientMockHandler);
        $stack->push($history);

        $this->client = new Client(['handler' => $stack]);
        $this->service = new UploadService(new DummyOutput(), 'http://test.localhost/upload', 'secretToken', $this->client);
    }

    public function test_upload_requestCorrect()
    {
        $distanceCollection = new DistanceCollection(null, [1, 2, 3]);

        $this->clientMockHandler->append(new Response(200));
        $this->service->upload($distanceCollection);

        self::assertCount(1, $this->requestContainer);
        self::assertEquals('POST', $this->requestContainer[0]['request']->getMethod());
        self::assertEquals('secretToken', $this->requestContainer[0]['request']->getHeaderLine('api-token'));
        self::assertEquals(json_encode($distanceCollection), $this->requestContainer[0]['request']->getBody());
    }

    public function test_upload_retryOnFailure()
    {
        $distanceCollection = new DistanceCollection(null, [1, 2, 3]);

        $this->clientMockHandler->append(new Response(500));
        $this->clientMockHandler->append(new Response(200));
        $this->service->upload($distanceCollection);

        self::assertCount(2, $this->requestContainer);
    }

    /**
     * @expectedException \SkydiveMarius\HWM\Client\Src\Upload\TimeoutExceededException
     * @expectedExceptionMessage Uploaded failed because timeout of 0 seconds has been exceeded
     */
    public function test_upload_retryUntilTimeout()
    {
        $distanceCollection = new DistanceCollection(null, [1, 2, 3]);

        $this->clientMockHandler->append(new Response(500));
        $this->service->setTimeout(0);
        $this->service->upload($distanceCollection);
    }
}