<?php

namespace Storekeeper\AssesFullstackDiscountTest;

use PHPUnit\Framework\TestCase;
use Storekeeper\AssesFullstackApi\Kernel;

class KernelTest extends TestCase
{
    public function testHandleIndexRequest(): void
    {
        $kernel = new Kernel();

        ob_start();
        $kernel->handleIndexRequest();
        $response = ob_get_clean();

        $this->assertNotEmpty($response, 'Kernel responded with data');
    }
}
