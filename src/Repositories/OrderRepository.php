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

    public function getAll(string $columns = '*'): array
    {
        $query = "SELECT " . $columns . " FROM orders";     
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createOrder($fields, $values): array
    {
        $query = $this->prepareInsertQuery($fields, $values,  $this->operators['comma']);
        $queryString = "INSERT INTO orders " . $query;

        $statement = $this->pdo->prepare($queryString);
        $statement->execute();

        return $this->getOrderByOrderId((int) $this->pdo->lastInsertId());
    }

    public function getOrderByOrderId(int $orderId, string $columns = '*')
    {
        $query = "SELECT " . $columns . " FROM orders WHERE id = " . $orderId;
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}