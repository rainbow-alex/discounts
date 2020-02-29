#!/usr/bin/env php
<?php

use Rainbow\Discounts\Application\Framework\DiscountsApiKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

require __DIR__ . "/../vendor/autoload.php";

$input = new ArgvInput();
$env = $input->getParameterOption(["--env", "-e"], getenv("SYMFONY_ENV") ?: "dev", true);
$debug = getenv("SYMFONY_DEBUG") !== "0" && !$input->hasParameterOption("--no-debug", true) && $env !== "prod";

$kernel = new DiscountsApiKernel($env, $debug);
$kernel->boot();
$app = new Application($kernel);
$app->run();
