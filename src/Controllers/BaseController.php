<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Controllers;

use Http\Response;
use Teapot\StatusCode;

class BaseController implements StatusCode
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    private function response($data, int $httpCode, $fieldsToAdd = [])
    {
        $response = array(
            'jsonrpc' => '2.0',
            'result' => $data
        );

        foreach ($fieldsToAdd as $key => $value) {
            $response[$key] = $value;
        }

        $this->response->setHeader("Content-Type", "application/json");
        $this->response->setStatusCode($httpCode);
        $this->response->setContent(json_encode($response));
    }

    protected function sendResponse(int $httpCode, $data, $fieldsToAdd = []) 
    {
        return $this->response($data, $httpCode, $fieldsToAdd);
    }
}