<?php
namespace SkydiveMarius\HWM\Client\Src\Upload;

/**
 * Interface Transmittable
 *
 * @package SkydiveMarius\HWM\Client\Src\Upload
 */
interface Transmittable
{
    /**
     * @return float
     */
    public function getAverage(): float;

    /**
     * @return int min. age in seconds
     */
    public function getMinimumAge(): int;
}