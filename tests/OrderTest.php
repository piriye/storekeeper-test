<?php

namespace Storekeeper\AssesFullstackApiTest;

use PHPUnit\Framework\TestCase;
use Storekeeper\AssesFullstackApi\Helpers\OrderHelper;

class OrderlTest extends TestCase
{
    public function testGetOrderTotalForSingleItem(): void
    {
        $orderItems = [
            [
                "quantity" => 2,
                "name" => "Red wine",
                "item_price" => "10.13"
            ]
        ];

        $total = OrderHelper::getOrderTotal($orderItems);
        $this->assertEquals(20.26, $total);
    }

    public function testGetOrderTotalForMultipleItems(): void
    {
        $orderItems = [
            [
                "quantity" => 2,
                "name" => "Red wine",
                "item_price" => "10.13"
            ],
            [
                "quantity" => 5,
                "name" => "Blue wine",
                "item_price" => "0.13"
            ]
        ];

        $total = OrderHelper::getOrderTotal($orderItems);
        $this->assertEquals(20.91, $total);
    }
}
