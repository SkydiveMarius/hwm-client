<?php
use SkydiveMarius\HWM\Client\Src\CLI\StartCommand;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/vendor/autoload.php';

$dotEnv = new \Dotenv\Dotenv(__DIR__);
$dotEnv->load();

$application = new Application('HWM');
$application->add(new StartCommand());
$application->run();