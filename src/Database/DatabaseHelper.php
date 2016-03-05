<?php

namespace Demo;

use Demo\DataBaseConnection;
use Dotenv\Dotenv;
use PDO;

class DataBaseHelper
{   
    private $DataBaseConnection;
    public function __construct(DataBaseConnection $DataBaseConnection)
    {
         $this->DataBaseConnection = $DataBaseConnection;
    }

    public function createTableData($tableName)
    {
        $connection = $this->DataBaseConnection;

        $stmt = 'CREATE TABLE IF NOT EXISTS '.$tableName.'(';
        $stmt .= ' id INT( 10 ) AUTO_INCREMENT PRIMARY KEY, 
                   name VARCHAR( 100 ), 
                   gender VARCHAR( 10 ), 
                   age INT( 10 ) NOT NULL, 
                   course VARCHAR( 150 ), 
                   position VARCHAR( 50 ) )';

        return $connection->exec($stmt);
    }
}