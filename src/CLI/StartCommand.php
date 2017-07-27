<?php
namespace SkydiveMarius\HWM\Client\Src\CLI;

use SkydiveMarius\HWM\Client\Src\Scheduling\SchedulingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

        $this->addOption('interval', 'i', InputOption::VALUE_OPTIONAL, 60);
        $this->addOption('correctionDelta', 'c', InputOption::VALUE_OPTIONAL, 0);
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
        $service->start((int) $input->getOption('interval'), (float) $input->getOption('correctionDelta'));
    }
}