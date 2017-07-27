<?php
namespace SkydiveMarius\HWM\Client\Src\Distance;

use Carbon\Carbon;

/**
 * Class DistanceCollection
 *
 * @package HWM\Measurement\Distance
 */
class DistanceCollection implements \JsonSerializable
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var Carbon
     */
    private $time;

    /**
     * @var array
     */
    private $values = [];

    /**
     * @var float
     */
    private $correctionDelta = 0;

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

        $values = $this->values;
        $base = array_pop($values);
        $deviation =  0;

        foreach ($values as $value) {
            $delta = abs($value - $base);

            if ($delta > $deviation) {
                $deviation = $delta;
            }
        }

        return $deviation <= $maxDeviation;
    }

    /**
     * @return float
     */
    public function getAverage(): float
    {
        if (empty($this->values)) {
            throw new \LogicException('Unable to build average of empty distance collection');
        }

        $average = array_sum($this->values) / count($this->values);
        return $average - $this->correctionDelta;
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

    /**
     * @return Carbon
     */
    public function getTime(): Carbon
    {
        return $this->time;
    }

    /**
     * @return int Age in seconds
     */
    public function getAge(): int
    {
        return $this->time->diffInSeconds(Carbon::now());
    }

    /**
     * @param float $correctionDelta
     */
    public function setCorrectionDelta(float $correctionDelta)
    {
        $this->correctionDelta = $correctionDelta;
    }

    /**
     * @return float
     */
    public function getCorrectionDelta(): float
    {
        return $this->correctionDelta;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'time'    => $this->time->format(self::DATE_FORMAT),
            'average' => $this->getAverage(),
            'values'  => $this->values
        ];
    }
}