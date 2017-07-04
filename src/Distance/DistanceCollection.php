<?php
namespace SkydiveMarius\HWM\Client\Src\Distance;

use Carbon\Carbon;

/**
 * Class DistanceCollection
 *
 * @package HWM\Measurement\Distance
 */
class DistanceCollection
{
    /**
     * @var Carbon
     */
    private $time;

    /**
     * @var array
     */
    private $values = [];

    /**
     * DistanceCollection constructor.
     *
     * @param Carbon|null $time
     * @param array       $values
     */
    public function __construct(Carbon $time = null, array $values = [])
    {
        $this->time = $time ?: Carbon::now();
        $this->values = $values;
    }

    /**
     * @param float $maxDeviation
     *
     * @return bool
     */
    public function isValid(float $maxDeviation = 5.0): bool
    {
        if (empty($this->values)) {
            return true;
        }

        $base = array_pop($this->values);
        $deviation =  0;

        foreach ($this->values as $value) {
            $delta = abs($value - $base);

            if ($delta > $deviation) {
                $deviation = $delta;
            }
        }

        return $deviation <= $maxDeviation;
    }

    /**
     * @param float $value
     */
    public function put(float $value)
    {
        $this->values[] = $value;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }
}