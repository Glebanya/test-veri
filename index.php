<?php

set_time_limit(0);
ini_set("memory_limit", "512M");
#error_reporting(0);

require __DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$loader = new YamlFileLoader(
	$containerBuilder = new ContainerBuilder(),
	new FileLocator(__DIR__ . DIRECTORY_SEPARATOR . 'config')
);
$loader->load('services.yaml');

$application = new Application('veri-test', '1.0');
foreach (['veri.attendance.calc.command'] as $command)
{
	$application->add(
		$containerBuilder->get($command)
	);
}

$application->run();






