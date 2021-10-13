<?php

namespace Storekeeper\AssesFullstackApi;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    public function handleRequest(Request $request): Response
    {
        $response = new JsonResponse([
            'title' => 'Assessment from api',
            'time' => time(),
            'path' => $request->getPathInfo(),
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
