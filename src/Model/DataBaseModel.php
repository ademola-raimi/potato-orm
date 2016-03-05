<?php

namespace Demo;

use Demo\DataBaseConnection;
use Demo\DataBaseQuery;
use Demo\DateBaseHelper;
use Dotenv\Dotenv;
use PDO;

class DataBaseModel
{
    protected $properties;
    protected $values;
    protected $tableName;
    protected $dataBaseModel;
    protected $DataBaseConnection;
    protected $arrayField = [];

    public function __construct(DataBaseConnection $DataBaseConnection)
    {
        $this->DataBaseConnection = $DataBaseConnection;
        $this->tableName          = $this->getClassName();
        $this->dataBaseModel      = new DatabaseHelper($this->createTableName);
    }

    public function __set($properties, $values)
    {
        $arrayField[$this->properties] = $values;
    }

    public function __get($properties)
    {
        $arrayField[$this->properties];
    }
    
    





    public function  getAll($)
    {
        $connection = $this->DataBaseConnection;
        $stmt = $connection->query("SELECT * FROM" . $tableName);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $connection = $this->DataBaseConnection;
        $stmt = $connection->query("SELECT * FROM" . $tableName . " WHERE id=" . $id);
            
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}