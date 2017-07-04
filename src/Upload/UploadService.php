<?php
namespace SkydiveMarius\HWM\Client\Src\Upload;

use GuzzleHttp\Client;
use SkydiveMarius\HWM\Client\Src\Distance\DistanceCollection;
use Symfony\Component\Console\Output\OutputInterface;
use Volantus\ConsoleOperations\Src\Output\OutputOperations;

/**
 * Class UploadService
 *
 * @package SkydiveMarius\HWM\Client\Src\Upload
 */
class UploadService
{
    use OutputOperations;

    /**
     * @var string
     */
    private $serverUrl;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @var Client
     */
    private $client;

    /**
     * Max. time to retry upload in case of error
     *
     * @var int in seconds
     */
    private $timeout = 60;

    /**
     * UploadService constructor.
     *
     * @param OutputInterface $output
     * @param string          $serverUrl
     * @param string          $authToken
     * @param Client          $client
     */
    public function __construct(OutputInterface $output, string $serverUrl = null, $authToken = null, Client $client = null)
    {
        $this->output = $output;
        $this->serverUrl = $serverUrl ?: getenv('SERVER_URL');
        $this->authToken = $authToken ?: getenv('AUTH_TOKEN');
        $this->client = $client ?: new Client();
    }

    /**
     * @param DistanceCollection $distanceCollection
     *
     * @throws TimeoutExceededException
     */
    public function upload(DistanceCollection $distanceCollection)
    {
        $successful = false;
        $this->writeInfoLine('UploadServe', 'Sending measured distance, average value => ' . round($distanceCollection->getAverage(), 2));

        do {
            try {
                $this->send($distanceCollection);
                $successful = true;
                $this->writeGreenLine('UploadService', 'Transmitted measured values successfully');
            } catch (\Throwable $e) {
                $this->writeRedLine('UploadService', 'Transmission failed => '. $e->getMessage());
            }
        } while (!$successful && $distanceCollection->getAge() <= $this->timeout);

        if (!$successful) {
            throw new TimeoutExceededException($this->timeout);
        }
    }

    /**
     * @param DistanceCollection $distanceCollection
     */
    private function send(DistanceCollection $distanceCollection)
    {
        $this->client->post($this->serverUrl, [
            'json'    => $distanceCollection,
            'headers' => [
                'api-token' => $this->authToken
            ]
        ]);
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }
}