<?php
namespace SkydiveMarius\HWM\Client\Src\Upload;

/**
 * Class TimeoutExceededException
 *
 * @package SkydiveMarius\HWM\Client\Src\Upload
 */
class TimeoutExceededException extends \Exception
{
    /**
     * TimeoutExceededException constructor.
     *
     * @param int $timeout
     */
    public function __construct(int $timeout)
    {
        $message = 'Uploaded failed because timeout of ' . $timeout . ' seconds has been exceeded';
        parent::__construct($message);
    }
}