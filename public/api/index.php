<?php

include_once __DIR__.'/../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');

$kernel = new \Storekeeper\AssesFullstackApi\Kernel();
$kernel->handleIndexRequest();
