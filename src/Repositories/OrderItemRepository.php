<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Repositories;

use PDO;
use Storekeeper\AssesFullstackApi\Repositories\BaseRepository;

class OrderItemRepository extends BaseRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createOrderItem($fields, $values): string
    {
        $query = $this->prepareInsertQuery($fields, $values,  $this->operators['comma']);
        $queryString = "INSERT INTO order_items " . $query;

        $statement = $this->pdo->prepare($queryString);
        $statement->execute();

        return $this->pdo->lastInsertId();
    }
}