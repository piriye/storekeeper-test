<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Controllers;

use Error;
use Http\Request;
use Http\Response;
use Valitron\Validator;

use Storekeeper\AssesFullstackApi\Controllers\BaseController;
use Storekeeper\AssesFullstackApi\Services\OrderService;

class OrderController extends BaseController
{
    private $request;
    private $response;

    public function __construct(
        Request $request, 
        Response $response, 
        OrderService $orderService
    ) {
        parent::__construct($response);

        $this->request = $request;
        $this->orderService = $orderService;
    }

    public function store()
    {
        $requestData = $this->request->getBodyParameters();
        
        $v = new Validator($requestData);
        $v->rule('required', ['name', 'price', 'quantity']);

        if ($v->validate()) {
            try {
                $orderData = $this->orderService->createOrder($requestData);
                $this->sendResponse(self::CREATED, $orderData, 'Successfully placed order');
            } catch (Error $e) {
                return $this->sendResponse(self::BAD_REQUEST, null, $e->getMessage());
            }
        } else {
            return $this->sendResponse(self::BAD_REQUEST, null, $v->errors());
        }
    }
}
