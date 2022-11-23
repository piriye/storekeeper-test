<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class HttpRequestHelper
{
    private $client;
    private $request;

    public function __construct()
    {
        $this->client = new Client();
        $this->request = new Request('POST', 'http://validate-api');
    }

    public function sendPost(array $postData)
    {
        try {
            $response = $this->client->send($this->request, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $postData
            ]);
    
            $responseBody = (string) $response->getBody();
            $jsonResponse = json_decode($responseBody, true);
    
            return $jsonResponse;
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            $jsonErrorResponse = json_decode($responseBodyAsString, true);

            throw new BadRequestException($jsonErrorResponse['error']);
        }
    }
}