<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Services;

use Ramsey\Uuid\Uuid;
use Storekeeper\AssesFullstackApi\Repositories\ItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderRepository;

class OrderService
{
    const VAT = 1;

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
        $orderUUID = Uuid::uuid4();

        $orderFields = ['number'];
        $orderValues = [$orderUUID->toString()];

        $order = $this->orderRepo->createOrder($orderFields, $orderValues);

        $totalPrice = 0;
    
        $orderDataArray = $this->objectToArray($orderData);

        foreach ($orderDataArray['items'] as $item) {
            $fetchedItem = $this->itemRepo->getItemByName($item['name']);

            if ($fetchedItem == null) {
                $itemFields = ['name', 'price'];
                $itemValues = [$item['name'], $item['item_price']];
    
                $itemId = $this->itemRepo->createItem($itemFields, $itemValues);
            } else {
                $itemId = $fetchedItem['id'];
            }

            $orderItemFields = ['order_id', 'item_id', 'quantity'];
            $orderItemValues = [$order['id'], $itemId, $item['quantity']];

            $this->orderItemRepo->createOrderItem($orderItemFields, $orderItemValues);

            $totalPrice += $item['quantity'] * $item['item_price'];
        }

        $totalPrice += self::VAT;
        $updatedOrder = $this->updateOrderById($order['id'], [ 'total' => $totalPrice ]);

        return $updatedOrder;
    }

    public function updateOrderById($orderId, $params)
    {
        $fields = array();
        $values = array();

        foreach ($params as $key => $value) {
            $fields[] = $key;
            $values[] = $value;
        }

        return $this->orderRepo->updateOrderById($orderId, $fields, $values);
    }

    public function objectToArray($obj) {
        if (is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;

            foreach($ret as &$item) {
                $item = $this->objectToArray($item);
            }
            return $ret;
        }  else {
            return $obj;
        }
    }
}

