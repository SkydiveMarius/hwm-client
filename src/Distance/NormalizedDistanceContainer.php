<?php
namespace SkydiveMarius\HWM\Client\Src\Distance;

use SkydiveMarius\HWM\Client\Src\Upload\Transmittable;

/**
 * Class NormalizationService
 *
 * @package SkydiveMarius\HWM\Client\Src\Distance
 */
class NormalizedDistanceContainer implements Transmittable
{
    /**
     * @var int
     */
    private $normDeepness;

    /**
     * @var DistanceCollection[]
     */
    private $measurements = [];

    /**
     * NormalizationService constructor.
     *
     * @param int $normDeepness
     */
    public function __construct(int $normDeepness)
    {
        $this->normDeepness = $normDeepness;
    }

    /**
     * @param DistanceCollection $measurement
     */
    public function put(DistanceCollection $measurement)
    {
        array_unshift($this->measurements, $measurement);

        while (count($this->measurements) > $this->normDeepness) {
            array_pop($this->measurements);
        }
    }

    /**
     * @return float
     */
    public function getAverage(): float
    {
        if (empty($this->measurements)) {
            throw new \RuntimeException('Unable to get average of empty NormalizedDistanceContainer');
        }

        $sum = 0;
        foreach ($this->measurements as $measurement) {
            $sum += $measurement->getAverage();
        }

        return $sum / count($this->measurements);
    }

    /**
     * @return int min. age in seconds
     */
    public function getMinimumAge(): int
    {
        if (empty($this->measurements)) {
            throw new \RuntimeException('Unable to get min. age of empty NormalizedDistanceContainer');
        }

        return $this->measurements[0]->getAge();
    }
}