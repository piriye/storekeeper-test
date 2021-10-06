<?php

include_once __DIR__.'/../../vendor/autoload.php';

$kernel = new \Storekeeper\AssesFullstackApi\Kernel();
$kernel->handleIndexRequest();
