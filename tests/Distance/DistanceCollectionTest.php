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
}