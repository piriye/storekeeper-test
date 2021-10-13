<?php

namespace Storekeeper\AssesFullstackApi;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    public function handleRequest(Request $request): Response
    {
        $pdo = $this->getDbConnection();
        $now = $this->getDbNow($pdo);
        $response = new JsonResponse([
            'title' => 'Assessment from api',
            'time' => time(),
            'dbtime' => $now,
            'path' => $request->getPathInfo(),
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    protected function getDbConnection(): \PDO
    {
        $dsn = getenv('DATABASE_DNS');
        $user = getenv('DATABASE_USER');
        $password = getenv('DATABASE_PASSWORD');
        $pdo = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);

        return $pdo;
    }

    protected function getDbNow(\PDO $pdo): string
    {
        $statement = $pdo->prepare('SELECT now();');
        $statement->execute();
        $now = $statement->fetchColumn(0);

        return $now;
    }
}
