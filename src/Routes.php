<?php declare(strict_types = 1);

return [
    ['POST', '/orders', ['Storekeeper\AssesFullstackApi\Controllers\OrderController', 'store']],
    ['POST', '/api', ['Storekeeper\AssesFullstackApi\Controllers\DispatchController', 'dispatch']],
];
