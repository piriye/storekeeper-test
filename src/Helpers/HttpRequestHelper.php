<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class HttpRequestHelper
{
    private $client;
    private $request;

    public function __construct()
    {
        $this->client = new Client();
        $this->request = new Request('POST', 'http://validate-api');
    }

    public function sendPost(string $url, array $postData)
    {
        $response = $this->client->send($this->request, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $postData
        ]);

        $responseBody = (string) $response->getBody();
        $jsonResponse = json_decode($responseBody, true);

        return $jsonResponse;
    }
}