<?php

namespace App\Repositories;

use App\Database\DB;

class BaseRepository extends DB{
    protected string $tableName;

    public function getAll(): array
    {
        $query=$this->select() . "ORDER BY name";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function select(){
        return "SELECT * FROM '{$this->tableName}'";
    }
}