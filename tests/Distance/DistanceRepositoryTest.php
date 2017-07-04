<?php
namespace SkydiveMarius\HWM\Client\Tests\Distance;

use PHPUnit\Framework\TestCase;
use SkydiveMarius\HWM\Client\Src\Distance\DistanceCollection;
use SkydiveMarius\HWM\Client\Src\Distance\DistanceRepository;
use SkydiveMarius\HWM\Client\Src\Distance\UltrasonicAdapter;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;

/**
 * Class DistanceRepositoryTest
 *
 * @package SkydiveMarius\HWM\Client\Tests\Distance
 */
class DistanceRepositoryTest extends TestCase
{
    /**
     * @var UltrasonicAdapter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $adapter;

    /**
     * @var DistanceRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->adapter = $this->getMockBuilder(UltrasonicAdapter::class)->disableOriginalConstructor()->getMock();
        $this->repository = new DistanceRepository(new DummyOutput(), $this->adapter);
    }

    public function test_measure_untilValid()
    {
        $this->adapter->expects(self::exactly(10))
            ->method('getDistance')
            ->willReturn(1, 1, 1, 1, 10, 5, 6, 7, 6, 5);

        $result = $this->repository->measure();
        self::assertInstanceOf(DistanceCollection::class, $result);
        self::assertCount(5, $result->getValues());
        self::assertEquals([5, 6, 7, 6, 5], $result->getValues());
    }
}