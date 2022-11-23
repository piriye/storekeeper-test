<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Services;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

use Storekeeper\AssesFullstackApi\Helpers\HttpRequestHelper;
use Storekeeper\AssesFullstackApi\Helpers\OrderHelper;
use Storekeeper\AssesFullstackApi\Repositories\ItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderRepository;

class OrderService
{
    const VAT = 1;

    private $httpRequestHelper;

    public function __construct(
        ItemRepository $itemRepo,
        OrderItemRepository $orderItemRepo,
        OrderRepository $orderRepo
    ) {
        $this->itemRepo = $itemRepo;
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;

        $this->httpRequestHelper = new HttpRequestHelper();
    }

    public function createOrder($orderData)
    {
        $orderDataArray = OrderHelper::objectToArray($orderData);

        $totalPrice = OrderHelper::getOrderTotal($orderDataArray['items']);

        if (!$this->validateOrder($orderDataArray['items'])) {
            throw new BadRequestException('Invalid order');
        };

        $orderUUID = Uuid::uuid4();

        $orderFields = ['number'];
        $orderValues = [$orderUUID->toString()];

        $order = $this->orderRepo->createOrder($orderFields, $orderValues);

        $totalPrice = 0;
    
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

    public function validateOrder($totalPrice) 
    {
        $url = "http://validate-api";
        $response = $this->httpRequestHelper->sendPost($url, [ "value" => $totalPrice ]);

        return $response['valid'];
    }
}

