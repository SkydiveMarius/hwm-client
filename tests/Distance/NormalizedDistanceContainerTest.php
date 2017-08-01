<?php
namespace SkydiveMarius\HWM\Client\Tests\Distance;

use PHPUnit\Framework\TestCase;
use SkydiveMarius\HWM\Client\Src\Distance\DistanceCollection;
use SkydiveMarius\HWM\Client\Src\Distance\NormalizedDistanceContainer;

/**
 * Class NormalizedDistanceContainerTest
 *
 * @package SkydiveMarius\HWM\Client\Tests\Distance
 */
class NormalizedDistanceContainerTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unable to get average of empty NormalizedDistanceContainer
     */
    public function test_getAverage_empty()
    {
        $container = new NormalizedDistanceContainer(3);
        $container->getAverage();
    }

    public function test_getAverage_calculatedCorrectly()
    {
        $container = new NormalizedDistanceContainer(10);
        $container->put(new DistanceCollection(null, [10]));
        $container->put(new DistanceCollection(null, [14]));
        $container->put(new DistanceCollection(null, [16]));
        $container->put(new DistanceCollection(null, [20]));

        self::assertEquals(15, $container->getAverage());
    }

    public function test_put_deepnessRespected()
    {
        $container = new NormalizedDistanceContainer(2);
        $container->put(new DistanceCollection(null, [100]));
        $container->put(new DistanceCollection(null, [200]));
        $container->put(new DistanceCollection(null, [15]));
        $container->put(new DistanceCollection(null, [20]));

        self::assertEquals(17.5, $container->getAverage());
    }
}