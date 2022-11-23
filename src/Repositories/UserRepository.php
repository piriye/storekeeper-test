<?php declare(strict_types = 1);

namespace Storekeeper\AssesFullstackApi\Repositories;

use PDO;
use Storekeeper\AssesFullstackApi\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    private $pdo;
    private $table = "users";

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createUser($fields, $values)
    {
        $query = $this->prepareInsertQuery($fields, $values,  $this->operators['comma']);
        $queryString = "INSERT INTO $this->table " . $query;

        $statement = $this->pdo->prepare($queryString);
        $statement->execute();

        return $this->getUserById((int) $this->pdo->lastInsertId());
    }

    public function getUserByName(string $name, string $columns = '*')
    {
        $query = "SELECT " . $columns . " FROM $this->table WHERE name = '" . $name . "'";
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $userId, string $columns = '*')
    {
        $query = "SELECT " . $columns . " FROM $this->table WHERE id = " . $userId;
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
