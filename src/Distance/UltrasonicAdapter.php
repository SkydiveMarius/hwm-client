<?php
namespace SkydiveMarius\HWM\Client\Src\Distance;

use Symfony\Component\Process\Process;

/**
 * Class UltrasonicAdapter
 *
 * @package SkydiveMarius\HWM\Client\Src\Distance
 */
class UltrasonicAdapter
{
    /**
     * @var string
     */
    private $binary = __DIR__ . '/../../bin/measure.py';

    /**
     * @return float
     */
    public function getDistance(): float
    {
        $process = new Process('python ' . $this->binary);
        return (float) trim($process->getOutput());
    }
}