<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Controllers;

use Error;
use Http\Request;
use Http\Response;
use Storekeeper\AssesFullstackApi\Controllers\BaseController;
use Storekeeper\AssesFullstackApi\Services\OrderService;

class OrderController extends BaseController
{
    private $request;
    private $response;

    public function __construct(Request $request, Response $response, OrderService $orderService)
    {
        parent::__construct($response);

        $this->request = $request;
        $this->orderService = $orderService;
    }

    public function store()
    {
        try {
            $requestData = $this->request->getBodyParameters();
    
            $orderData = $this->orderService->createOrder($requestData);
            $this->sendResponse(201, $orderData, 'Successfully placed order');
        } catch (Error $e) {
            $this->sendResponse(400, null, $e->getMessage());
        }
    }
}
