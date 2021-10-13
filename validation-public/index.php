<?php

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

include_once __DIR__.'/../vendor/autoload.php';

try {
    $request = Request::createFromGlobals();
    if ('POST' !== $request->getMethod()) {
        throw new MethodNotAllowedException(['POST']);
    }

    $content = $request->getContent();
    $json = json_decode($content, true);

    if (is_null($json)) {
        throw new BadRequestException('Failed to decode json: '.json_last_error_msg());
    }
    if (!array_key_exists('value', $json) && is_numeric($json['value'])) {
        throw new BadRequestException('.value needs to be a numeric');
    }
    $valid = $json['value'] <= 1000 && $json['value'] > 0;
    sleep(mt_rand(1, 3)); // simulate advanced costly calculations and checks

    $response = new JsonResponse([
        'valid' => $valid,
    ]);
} catch (MethodNotAllowedException $e) {
    $response = new JsonResponse([
        'error' => 'Only '.implode(',', $e->getAllowedMethods()).' is allowed',
        'class' => get_class($e),
    ], 405);
} catch (BadRequestException $e) {
    $response = new JsonResponse([
        'error' => $e->getMessage(),
        'class' => get_class($e),
    ], 400);
} catch (Throwable $e) {
    $response = new JsonResponse([
        'error' => $e->getMessage(),
        'class' => get_class($e),
    ], 500);
}

$response->prepare($request);
$response->send();
