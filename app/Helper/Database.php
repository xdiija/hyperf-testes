<?php
declare(strict_types=1);

namespace App\Helper;

use App\Exception\QueryException;
use Swoole\Coroutine\PostgreSQL;
use Hyperf\DB\DB;

class Database
{

    public function run(string $query){
        return $this->query($query);
    }

    /**
     * @throws QueryException
     */
    public function query(string $query){
        try {
            return Db::query($query);
        } catch (\PDOException $exception) {
            throw QueryException::failed($exception->getMessage(), $query, $exception);
        }
    }

    public function insert(string $table, array $values){

        // CHECK IF IS A MULTI INSERT
        if(!empty($values[0])){
            $lines = [];
            foreach ($values as $line){
                $result = $this->insertFormat($line);
                $lines[] = $result["fieldValues"];
            }
            $valuesToInsert = implode(",", $lines);
        }else{
            $result = $this->insertFormat($values);
            $valuesToInsert = $result["fieldValues"];
        }

        $columns = ' (' . implode(", ", $result["fields"]) .  ') VALUES ';
        $query = 'INSERT INTO ' . $table . $columns . $valuesToInsert;

        $this->query($query);

        return true;
    }

    private function insertFormat(array $values): array
    {
        $fields = [];
        $fieldValues = [];

        foreach ($values as $key => $val) {
            $fields[] = $key;
            $fieldValues[] = is_string($val) ? "'" . str_replace("'", "\"", $val) . "'" : $val;
        }

        return ["fields" => $fields, "fieldValues" => '(' . implode(", ", $fieldValues) . ')'];
    }

}

