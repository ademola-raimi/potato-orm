<?php

namespace Demo;

use Demo\DataBaseConnection;
use Dotenv\Dotenv;
use PDO;

class DataBaseQuery
{
    protected $properties;
    protected $values;
    protected $tableName;
    protected $DataBaseConnection;
    protected $arrayField = [];

    public function __construct(DataBaseConnection $DataBaseConnection)
    {
        $this->DataBaseConnection = $DataBaseConnection;
        // $this->tableName = $this->getClassName();
        // $this->databaseModel = new DatabaseHandler($this->tableName);
    }

    public function __set($properties, $values)
    {
        $arrayField[$this->properties] = $values;
    }

    public function __get($properties)
    {
        return $arrayField[$this->properties];
    }
    
    public function  getAll()
    {
        $connection = $this->DataBaseConnection;
        $stmt = $connection->query("SELECT * FROM cars");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $connection = $this->DataBaseConnection;
        $stmt = $connection->query('SELECT * FROM cars WHERE id=' . $id);
            
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function createData()
    // {
    //     $connection = $this->getConnection();
    //     $stmt = $connection->query("INSERT INTO cars ($name, $model, $color, $year) VALUES ($arrayField($this->properties)");


    //     return true;
    // }
    


    public function updateData($id, $name, $model, $color, $year)
    {
        $connection = $this->DataBaseConnection;
        $stmt = $connection->prepare("UPDATE table SET name=?, model=?, color=?, year=? WHERE id=?");
        $stmt->execute($arrayField($name, $model, $color, $year, $id));
        $affected_rows = $stmt->rowCount();
        
        return true;
    }

    public function deleteData($id)
    {
        $connection = $this->DataBaseConnection;
        $stmt = $connection->query("DELETE cars WHERE id=' . $id ");

        return true;
    }

}