<?php
namespace SkydiveMarius\HWM\Client\Src\CLI;

use SkydiveMarius\HWM\Client\Src\Scheduling\SchedulingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 *
 * @package SkydiveMarius\HWM\Client\Src\CLI
 */
class StartCommand extends Command
{
    protected function configure()
    {
        $this->setName('start');
        $this->setDescription('Starts the measurement cycle');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = new SchedulingService($output);
        $service->start();
    }
}