<?php

namespace Storekeeper\AssesFullstackDiscountTest;

use PHPUnit\Framework\TestCase;
use Storekeeper\AssesFullstackApi\Kernel;
use Symfony\Component\HttpFoundation\Request;

class KernelTest extends TestCase
{
    public function testHandleIndexRequest(): void
    {
        $kernel = new Kernel();
        $response = $kernel->handleRequest(new Request());

        $this->assertNotEmpty($response->getContent(), 'Kernel responded with data');
    }
}
