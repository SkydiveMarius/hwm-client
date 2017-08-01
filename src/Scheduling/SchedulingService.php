<?php
namespace SkydiveMarius\HWM\Client\Src\Scheduling;

use SkydiveMarius\HWM\Client\Src\Distance\DistanceRepository;
use SkydiveMarius\HWM\Client\Src\Distance\NormalizedDistanceContainer;
use SkydiveMarius\HWM\Client\Src\Upload\UploadService;
use Symfony\Component\Console\Output\OutputInterface;
use Volantus\ConsoleOperations\Src\Output\OutputOperations;

/**
 * Class SchedulingService
 *
 * @package SkydiveMarius\HWM\Client\Src\Scheduling
 */
class SchedulingService
{
    use OutputOperations;

    /**
     * @var DistanceRepository
     */
    private $distanceRepository;

    /**
     * @var UploadService
     */
    private $uploadService;

    /**
     * @var NormalizedDistanceContainer
     */
    private $normalizationContainer;

    /**
     * @var int
     */
    private $cycle = 0;

    /**
     * SchedulingService constructor.
     *
     * @param OutputInterface    $output
     * @param DistanceRepository $distanceRepository
     * @param UploadService      $uploadService
     * @param int                $normDeepness
     */
    public function __construct(OutputInterface $output, DistanceRepository $distanceRepository = null, UploadService $uploadService = null, int $normDeepness)
    {
        $this->output = $output;
        $this->distanceRepository = $distanceRepository ?: new DistanceRepository($this->output);
        $this->uploadService = $uploadService ?: new UploadService($this->output);
        $this->normalizationContainer = new NormalizedDistanceContainer($normDeepness);
    }

    /**
     * @param int   $interval
     * @param float $correctionDelta
     */
    public function start(int $interval = 60, float $correctionDelta = 0)
    {
        while (true) {
            $this->cycle++;
            $this->writeInfoLine('SchedulingService', 'Starting cycle ' . $this->cycle);

            try {
                $distanceCollection = $this->distanceRepository->measure();
                $this->writeInfoLine('SchedulingService', 'Applying correction delta: ' . $correctionDelta);
                $distanceCollection->setCorrectionDelta($correctionDelta);
                $this->normalizationContainer->put($distanceCollection);

                $this->uploadService->upload($this->normalizationContainer);

                $delta = $interval - $distanceCollection->getAge();
                if ($delta > 0) {
                    $this->writeInfoLine('SchedulingService', 'Sleeping ' . $delta . ' seconds until next cycle');
                    sleep($delta);
                }
            } catch (\Throwable $e) {
                $this->writeRedLine('SchedulingService', 'Cycle ' . $this->cycle . ' failed => ' . $e->getMessage());
                sleep(1);
            }
        }
    }
}