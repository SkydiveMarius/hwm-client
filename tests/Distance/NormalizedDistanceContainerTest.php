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

    public function test_getMinimumAge_correct()
    {
        $container = new NormalizedDistanceContainer(10);

        /** @var DistanceCollection|\PHPUnit_Framework_MockObject_MockObject $distanceCollection1 */
        $distanceCollection1 = $this->getMockBuilder(DistanceCollection::class)->disableOriginalConstructor()->getMock();
        $distanceCollection1->method('getAge')->willReturn(50);

        /** @var DistanceCollection|\PHPUnit_Framework_MockObject_MockObject $distanceCollection2 */
        $distanceCollection2 = $this->getMockBuilder(DistanceCollection::class)->disableOriginalConstructor()->getMock();
        $distanceCollection2->method('getAge')->willReturn(10);

        $container->put($distanceCollection1);
        $container->put($distanceCollection2);

        self::assertEquals(10, $container->getMinimumAge());
    }
}