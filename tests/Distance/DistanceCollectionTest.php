<?php
namespace SkydiveMarius\HWM\Client\Tests\Distance;

use PHPUnit\Framework\TestCase;
use SkydiveMarius\HWM\Client\Src\Distance\DistanceCollection;

/**
 * Class DistanceCollectionTest
 *
 * @package HWM\Measurement\Tests\Distance
 */
class DistanceCollectionTest extends TestCase
{
    public function test_isValid_true()
    {
        $collection = new DistanceCollection(null, [5, 6, 7, 4, 3]);
        self::assertTrue($collection->isValid(5));
    }

    public function test_isValid_false()
    {
        $collection = new DistanceCollection(null, [5, 7, 11, 6, 1]);
        self::assertFalse($collection->isValid(5));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Unable to build average of empty distance collection
     */
    public function test_getAverage_empty()
    {
        $collection = new DistanceCollection();
        $collection->getAverage();
    }

    public function test_getAverage_correct()
    {
        $collection = new DistanceCollection(null, [10, 14, 16, 20]);
        self::assertEquals(15, $collection->getAverage());
    }

    public function test_getAverage_correctionDeltaApplied()
    {
        $collection = new DistanceCollection(null, [10, 14, 16, 20]);
        $collection->setCorrectionDelta(2);

        self::assertEquals(13, $collection->getAverage());
    }
}