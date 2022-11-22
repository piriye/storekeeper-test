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

    private function response($data, int $httpCode, $msg = null)
    {
        $msg = ($msg) ? $msg : '';

        if ($msg) {
            $response = array(
                'code' => $httpCode,
                'message' => $msg,
                'data' => $data
            );
        }

        $this->response->setHeader("Content-Type", "application/json");
        $this->response->setStatusCode($httpCode);
        $this->response->setContent(json_encode($response));
    }

    protected function sendResponse(int $httpCode, $data, $msg = null) 
    {
        return $this->response($data, $httpCode, $msg);
    }
}