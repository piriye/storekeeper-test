<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Services;

use Ramsey\Uuid\Uuid;
use Storekeeper\AssesFullstackApi\Repositories\ItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderRepository;

class OrderService
{
    public function __construct(
        ItemRepository $itemRepo,
        OrderItemRepository $orderItemRepo,
        OrderRepository $orderRepo
    ) {
        $this->itemRepo = $itemRepo;
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
    }

    public function createOrder($orderData)
    {
        $item = $this->itemRepo->getItemByName($orderData['name']);

        if ($item == null) {
            $itemFields = ['name', 'price'];
            $itemValues = [$orderData['name'], $orderData['price']];

            $itemId = $this->itemRepo->createItem($itemFields, $itemValues);
        } else {
            $itemId = $item['id'];
        }
        
        $orderUUID = Uuid::uuid4();
        $totalPrice = $orderData['price'] * $orderData['quantity'];

        $orderFields = ['order_number', 'total_price'];
        $orderValues = [$orderUUID->toString(), $totalPrice];

        $order = $this->orderRepo->createOrder($orderFields, $orderValues);

        $orderItemFields = ['order_id', 'item_id', 'quantity'];
        $orderItemValues = [$order['id'], $itemId, $orderData['quantity']];
        $this->orderItemRepo->createOrderItem($orderItemFields, $orderItemValues);

        return $order;
    }
}

