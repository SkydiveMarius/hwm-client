<?php
namespace SkydiveMarius\HWM\Client\Src\Distance;

use Symfony\Component\Console\Output\OutputInterface;
use Volantus\ConsoleOperations\Src\Output\OutputOperations;

/**
 * Class DistanceRepository
 *
 * @package SkydiveMarius\HWM\Client\Src\Distance
 */
class DistanceRepository
{
    use OutputOperations;

    /**
     * @var UltrasonicAdapter
     */
    private $adapter;

    /**
     * DistanceRepository constructor.
     *
     * @param OutputInterface   $output
     * @param UltrasonicAdapter $adapter
     */
    public function __construct(OutputInterface $output, UltrasonicAdapter $adapter = null)
    {
        $this->adapter = $adapter ?: new UltrasonicAdapter();
        $this->output = $output;
    }

    /**
     * @param int $points
     *
     * @return DistanceCollection
     */
    public function measure(int $points = 5): DistanceCollection
    {
        do {
            $distance = new DistanceCollection();

            for ($i = 0; $i < $points; $i++) {
                $dataPoint = $this->adapter->getDistance();
                $distance->put($dataPoint);
                $this->writeInfoLine('UltrasonicAdapter', 'Measured data point ' . $dataPoint);
            }

            $collectionValid = $distance->isValid();
            if ($collectionValid) {
                $this->writeGreenLine('DistanceRepository', 'Collection of measured distance data points is valid');
            } else {
                $this->writeGreenLine('DistanceRepository', 'Collection of measured distance data points is not valid, will retry measurement ...');
            }
        } while (!$collectionValid);

        return $distance;
    }


}