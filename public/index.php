<?php

declare(strict_types=1);

use Rainbow\Discounts\Api\Framework\AppKernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . "/../vendor/autoload.php";

$env = getenv("SYMFONY_ENV") ?: "dev";
$debug = getenv("SYMFONY_DEBUG") !== 0;

if ($debug)
{
	Debug::enable();
}

$kernel = new AppKernel($env, $debug);
$kernel->boot();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
$kernel->shutdown();
