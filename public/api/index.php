<?php

include_once __DIR__.'/../../vendor/autoload.php';

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$kernel = new \Storekeeper\AssesFullstackApi\Kernel();
$response = $kernel->handleRequest($request);
$response->prepare($request);
$response->send();
