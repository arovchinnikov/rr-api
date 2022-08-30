<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Repositories;

use App\Common\Domain\Entities\BaseModel;
use Core\Components\Data\Env;
use Core\Components\Database\Connection;
use Core\Components\Database\Exceptions\ConnectionException;
use Core\Exceptions\CoreException;
use Throwable;

class PostgresRepository extends BaseRepository
{
    protected Connection $connection;

    /**
     * @throws ConnectionException
     */
    public function __construct()
    {
        $connection = new Connection();
        $connection->connect(
            'pgsql',
            Env::get('POSTGRES_HOST'),
            Env::get('POSTGRES_PORT'),
            Env::get('POSTGRES_DB'),
            Env::get('POSTGRES_USER'),
            Env::get('POSTGRES_PASSWORD')
        );

        $this->connection = $connection;
    }

    /**
     * @throws CoreException
     */
    public function saveModel(BaseModel $model): ?BaseModel
    {
        $fields = $model->valuesToQuery();

        $keys = array_keys($fields);
        $values = array_values($fields);

        $preparedValues = [];
        foreach ($keys as $value) {
            $preparedValues[] = ':' . $value;
        }

        $sql = 'INSERT INTO ' . $model::$table . '(' . implode(', ', $keys) . ') 
        VALUES (' . implode(', ', $preparedValues) . ') RETURNING *;';

        try {
            $fields = $this->connection->query($sql, $values)[0];
        } catch (Throwable $e) {
            throw new CoreException($e->getMessage());
        }

        $modelName = $model::class;

        /** @var \App\Common\Domain\Entities\BaseModel $newModel */
        $newModel = new $modelName();
        $newModel->setValues($fields);

        return $newModel;
    }
}
