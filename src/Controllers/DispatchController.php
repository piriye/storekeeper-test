<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Controllers;

use Http\Request;
use Http\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Valitron\Validator;

use Storekeeper\AssesFullstackApi\Controllers\BaseController;
use Storekeeper\AssesFullstackApi\Exceptions\UnableToPlaceOrderException;
use Storekeeper\AssesFullstackApi\Services\OrderService;
use Storekeeper\AssesFullstackApi\Services\AuthService;

class DispatchController extends BaseController
{
    private $request;
    private $response;

    public function __construct(
        Request $request, 
        Response $response, 
        OrderService $orderService, 
        AuthService $authService
    ) {
        parent::__construct($response);

        $this->request = $request;
        $this->authService = $authService;
        $this->orderService = $orderService;
    }

    public function dispatch()
    {
        $requestData = $this->request->getBodyParameters();
        
        $v = new Validator($requestData);
        $v->rule('required', ['jsonrpc', 'method', 'params']);

        if ($v->validate()) {
            try {
                $fieldsToAdd = [];
    
                if (array_key_exists("id", $requestData)) {
                    $fieldsToAdd = [ "id" => $requestData['id'] ];
                }
    
                switch ($requestData['method']) {
                    case "info":
                        $fieldsToAdd = [ "valid" => false ];
                        $this->sendResponse(self::OK, [], $fieldsToAdd);
                        break;
    
                    case "login":
                        $userData = $this->authService->login($requestData['params']);
                        $this->sendResponse(self::OK, $userData, $fieldsToAdd);
                        break;
    
                    case "order":
                        $orderData = $this->orderService->createOrder($requestData['params']);
                        $this->sendResponse(self::OK, $orderData, $fieldsToAdd);
                        break;
                }
            } catch (UnableToPlaceOrderException $e) {
                return $this->sendResponse(self::BAD_REQUEST, [ 'message' => $e->getMessage()]);
            } catch (BadRequestException $e) {
                return $this->sendResponse(self::BAD_REQUEST, [ 'message' => $e->getMessage()]);
            }
        } else {
            return $this->sendResponse(self::BAD_REQUEST, $v->errors());
        }
    }
}
