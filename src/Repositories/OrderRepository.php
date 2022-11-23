<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Repositories;

use PDO;
use Storekeeper\AssesFullstackApi\Repositories\BaseRepository;

class OrderRepository extends BaseRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createOrder($fields, $values): array
    {
        $query = $this->prepareInsertQuery($fields, $values,  $this->operators['comma']);
        $queryString = "INSERT INTO orders " . $query;

        $statement = $this->pdo->prepare($queryString);
        $statement->execute();

        return $this->getOrderById((int) $this->pdo->lastInsertId());
    }

    public function getOrderById(int $orderId, string $columns = '*')
    {
        $query = "SELECT " . $columns . " FROM orders WHERE id = " . $orderId;
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateOrderById(int $orderId, array $fields, array $values) {
        $query = $this->prepareQuery($fields, $values, $this->operators['comma']);
        $queryString = "UPDATE orders SET " . $query . " WHERE id = " . $orderId;

        $statement = $this->pdo->prepare($queryString);
        $statement->execute();

        return $this->getOrderById((int) $orderId);
    }
}