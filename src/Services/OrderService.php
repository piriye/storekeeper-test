<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Services;

use Ramsey\Uuid\Uuid;
use Storekeeper\AssesFullstackApi\Exceptions\UnableToPlaceOrderException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

use Storekeeper\AssesFullstackApi\Helpers\HttpRequestHelper;
use Storekeeper\AssesFullstackApi\Helpers\OrderHelper;
use Storekeeper\AssesFullstackApi\Repositories\ItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderItemRepository;
use Storekeeper\AssesFullstackApi\Repositories\OrderRepository;
use Storekeeper\AssesFullstackApi\Services\AuthService;

class OrderService
{
    const VAT = 1;

    private $httpRequestHelper;

    public function __construct(
        AuthService $authService,
        ItemRepository $itemRepo,
        OrderItemRepository $orderItemRepo,
        OrderRepository $orderRepo
    ) {
        $this->authService = $authService;

        $this->itemRepo = $itemRepo;
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;

        $this->httpRequestHelper = new HttpRequestHelper();
    }

    public function createOrder($orderData)
    {
        $orderDataArray = OrderHelper::objectToArray($orderData);

        $orderFields = [];
        $orderValues = [];

        if (array_key_exists("user", $orderDataArray)) {
            $user = $this->authService->login(['user' => $orderDataArray['user']]);
            
            $canPlaceOrder = $this->checkIfUserCanPlaceOrder($user['id']);

            if (!$canPlaceOrder) {
                throw new UnableToPlaceOrderException('Wait at least 5 seconds before placing a new order');
            }

            array_push($orderFields, 'placed_by');
            array_push($orderValues, $user['id']);
        }

        $totalPrice = OrderHelper::getOrderTotal($orderDataArray['items']);

        $this->validateOrder($orderDataArray['items']);

        $orderUUID = Uuid::uuid4();

        array_push($orderFields, 'number');
        array_push($orderValues, $orderUUID->toString());

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
        $response = $this->httpRequestHelper->sendPost([ "value" => (int) $totalPrice ]);

        if (!$response['valid']) {
            throw new BadRequestException('Invalid order');
        }

        $totalForLastXSeconds = (float) $this->orderRepo->getOrderTotalForLastXSeconds()['sum'];
        
        if ($totalForLastXSeconds > 5000) {
            throw new UnableToPlaceOrderException('Total orders have exceeded 5000, please wait 30 seconds and try again');
        }
    }

    public function checkIfUserCanPlaceOrder($userId)
    {
        $currentTimestamp = time();

        $order = $this->orderRepo->getLatestOrderByUser($userId);

        if ($order == false) {
            return true;
        }

        $orderTimestamp = strtotime($order['updated_at']);

        $diff = $currentTimestamp - $orderTimestamp;

        if ($diff >= 5) {
            return true;
        }

        return false;
    }
    
}

