<?php
declare(strict_types=1);

namespace App\Helper;

use App\Exception\QueryException;
use Swoole\Coroutine\PostgreSQL;
use Hyperf\DB\DB;

class Database
{
    /**
     * Executa uma query que busca por multiplos resultados.
     * @throws QueryException
     */
    public function query(string $query, array $bindings = [])
    {
        try {
            return Db::query($query, $bindings);
        } catch (\PDOException $exception) {
            throw QueryException::failed($exception->getMessage(), $query, $exception);
        }
    }

    /**
     * Executa uma query que busca por um único resultado.
     * @throws QueryException
     */
    public function fetch(string $query, array $bindings = [])
    {
        try {
            return Db::fetch($query, $bindings);
        } catch (\PDOException $exception) {
            throw QueryException::failed($exception->getMessage(), $query, $exception);
        }
    }

    /**
     * Executa uma query que retorna a quantidade de registros afetados.
     * @throws QueryException
     */
    public function execute(string $sql): int
    {
        try {
            return DB::execute($sql);
        } catch (\PDOException $exception) {
            throw QueryException::failed($exception->getMessage(), $sql, $exception);
        }
    }

   /**
     * Inserts data into a table using parameter binding.
     * @throws QueryException
     */
    public function insert(string $table, array $values)
    {
        $isMultiInsert = !empty($values[0]) && is_array($values[0]);
        
        if ($isMultiInsert) {
            // Handle multiple inserts
            return $this->bulkInsert($table, $values);
        }

        // Handle single insert
        $columns = implode(', ', array_keys($values));
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        
        // Separate raw expressions from bindings
        $bindings = [];
        $placeholdersArray = [];
        foreach ($values as $value) {
            if ($value instanceof RawExpression) {
                $placeholdersArray[] = (string)$value;
            } else {
                $placeholdersArray[] = '?';
                $bindings[] = $value;
            }
        }
        
        $placeholders = implode(', ', $placeholdersArray);
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders}) RETURNING *";
        
        return $this->fetch($query, $bindings);
    }

    /**
     * Handles bulk inserts with parameter binding.
     */
    private function bulkInsert(string $table, array $rows): array
    {
        if (empty($rows)) {
            return [];
        }

        $columns = implode(', ', array_keys($rows[0]));
        $placeholders = [];
        $bindings = [];

        foreach ($rows as $row) {
            $rowPlaceholders = [];
            foreach ($row as $value) {
                if ($value instanceof RawExpression) {
                    $rowPlaceholders[] = (string)$value;
                } else {
                    $rowPlaceholders[] = '?';
                    $bindings[] = $value;
                }
            }
            $placeholders[] = '(' . implode(', ', $rowPlaceholders) . ')';
        }

        $query = "INSERT INTO {$table} ({$columns}) VALUES " . implode(', ', $placeholders) . " RETURNING *";
        
        return $this->query($query, $bindings);
    }

}

