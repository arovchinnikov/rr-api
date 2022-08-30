<?php

declare(strict_types=1);

namespace Core\Components\Database;

use Core\Components\Database\Exceptions\ConnectionException;
use PDO;
use PDOException;

class Connection
{
    private PDO $pdo;

    public function query(string $query, $params = []): array|bool|int|null
    {
        $preparedQuery = $this->pdo->prepare($query);
        $preparedQuery->execute($params);

        $result = $preparedQuery->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            return null;
        }

        if (count($result) === 1) {
            if (empty($result[0])) {
                return true;
            }

            if (isset($result[0]['count'])) {
                return $result[0]['count'];
            }

            return $result[0];
        }

        return $result;
    }

    /**
     * @throws ConnectionException
     */
    public function connect(
        string $driver,
        string $host,
        string $port,
        string $dbName,
        string $dbUser,
        string $dbPassword
    ): self {
        if (!empty($this->pdo)) {
            ConnectionException::alreadyConnected();
        }

        try {
            $this->pdo = new PDO(
                $driver . ':host=' . $host . ';port=' . $port . ';dbname=' . $dbName,
                $dbUser,
                $dbPassword,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            ConnectionException::connectionFailed($e);
        }

        return $this;
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
