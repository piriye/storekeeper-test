<?php declare(strict_types = 1);

return [
    ['POST', '/api', ['Storekeeper\AssesFullstackApi\Controllers\DispatchController', 'dispatch']],
    ['POST', '/api/', ['Storekeeper\AssesFullstackApi\Controllers\DispatchController', 'dispatch']],
];
