<?php

namespace Demo;

use Demo\DataBaseConnection;
use Dotenv\Dotenv;
use PDO;

class DataBaseHelper
{
    public function __construct(DataBaseConnection $dataBaseConnection)
    {
        $this->DataBaseConnection = $dataBaseConnection;
    }

    public function getConnection()
    {
        return $this->DataBaseConnection();
    }

    public function createTable($tableName)
    {
        $connection = $this->getConnection();
        $stmt = 'CREATE TABLE IF NOT EXISTS '.$tableName.'(';
        $stmt .= ' id INT( 11 ) AUTO_INCREMENT PRIMARY KEY, 
                  name VARCHAR( 100 ), 
                  gender VARCHAR( 10 ), 
                  alias VARCHAR( 150 ) NOT NULL, 
                  class VARCHAR( 150 ), stack VARCHAR( 50 ) )';

        return $connection->exec($stmt);
        
    }
}