<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Services;

use Carbon\Carbon;
use Storekeeper\AssesFullstackApi\Repositories\OrderRepository;

class OrderService
{
    public function __construct(
        OrderRepository $orderRepo
    ) {
        $this->orderRepo = $orderRepo;
    }

    public function createOrder($orderData)
    {
        $fields = ['quantity', 'name', 'price'];
        $values = [$orderData['quantity'], $orderData['name'], $orderData['price']];

        $id = $this->orderRepo->createOrder($fields, $values);
    }
}

