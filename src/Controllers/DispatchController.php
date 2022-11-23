<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Controllers;

use Error;
use Http\Request;
use Http\Response;
use Valitron\Validator;

use Storekeeper\AssesFullstackApi\Controllers\BaseController;
use Storekeeper\AssesFullstackApi\Services\OrderService;

class DispatchController extends BaseController
{
    private $request;
    private $response;

    public function __construct(Request $request, Response $response, OrderService $orderService)
    {
        parent::__construct($response);

        $this->request = $request;
        $this->orderService = $orderService;
    }

    public function dispatch()
    {
        $requestData = $this->request->getBodyParameters();
        
        $v = new Validator($requestData);
        $v->rule('required', ['jsonrpc', 'method', 'params']);

        if ($v->validate()) {
            switch ($requestData['method']) {
                case "info":
                    $this->sendResponse(self::OK, [], 'Not yet implemented');
                    break;

                case "login":
                    $this->sendResponse(self::OK, [], 'Not yet implemented');
                    break;

                case "order":
                    $orderData = $this->orderService->createOrder($requestData['params']);
                    $this->sendResponse(self::OK, $orderData);
                    break;
            }
        } else {
            return $this->sendResponse(self::BAD_REQUEST, null, $v->errors());
        }
    }
}
