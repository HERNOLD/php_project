<?php

namespace App\Repositories;

use App\Database\DB;

class BaseRepository extends DB
{
    protected string $tableName;

    // public function create(array $data): ?int
    // {
    //     $sql = "INSERT INTO `%s` (%s) VALUES (%s)";
    //     $fields = '';
    //     $values = '';
    //     // foreach ( )
    //     // {

    //     // }
    // }


    public function select()
    {
        return "SELECT * FROM `{$this->tableName}` ";
    }


    public function getAll(): array
    {
        $query = $this->select();

        // ". ORDER BY name";
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): array
    {
        $query = $this->select() . " WHERE id=$id";

        $result = $this->mysqli->query($query)->fetch_assoc();
        if (!$result) {
            $result = [];
        }

        return $result;
    }

    function deleteById($id)
    {
        return $this->mysqli->query("DELETE FROM `{$this->tableName}` WHERE id=$id");
    }

    public function create(array $data): ?int
    {
        $sql="INSERT INTO `%s` (%s) VALUES (%s)";
        $fields='';
        $values='';
        foreach($data as $field => $value){
            if($fields >''){
                $fields.=','.$fields;
            }
            else{
                $fields.=$field;
            }
            if($values>''){
                $values.=','."'$value'";
            }
            else{
                $values.="'$value'";
            }
        }
        $sql=sprintf($sql, $this->tableName, $fields, $values);
        $this->mysqli->query($sql);
 
        $lastInserted=$this->mysqli->query("SELECT LAST_INSERT_ID() id;")->fetch_assoc();
 
        return $lastInserted['id'];
    }

    public function update(int $id, array $data){
        $query="UPDATE '($this->tableName)' SET %s WHERE id=$id;";
        $set='';
        foreach ($data as $field => $value){
            if($set>''){
                $set.=", $field = '$value'";
            }
            else{
                $set.="$field='$value'";
            }
        }

        $query=sprintf($query, $set);
        $this->mysqli->query($query);

        return $this->find($id);
    }

    public function find(int $id){
        $query="SELECT * FROM {$this->tableName} WHERE id=$id";
        return $this->mysqli->query($query)->fetch_assoc();
    }


    // public function countAll()
    // {

    // }

    // public function getCount()
    // {

    // }


}

