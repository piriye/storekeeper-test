<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Repositories;

use PDO;
use Storekeeper\AssesFullstackApi\Repositories\BaseRepository;

class ItemRepository extends BaseRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createItem($fields, $values): string
    {
        $query = $this->prepareInsertQuery($fields, $values,  $this->operators['comma']);
        $queryString = "INSERT INTO items " . $query;

        $statement = $this->pdo->prepare($queryString);
        $statement->execute();

        return $this->pdo->lastInsertId();
    }

    public function getItemByName(string $name, string $columns = '*')
    {
        $query = "SELECT " . $columns . " FROM items WHERE name = '" . $name . "'";
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}