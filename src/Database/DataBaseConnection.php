<?php

namespace Demo;

 use Dotenv\Dotenv;
 use PDO;

class DataBaseConnection extends PDO
{
  private $servername;
  private $username;
  private $password;
  private $driver;
  private $dbname;

  public function __construct()
  {
      $this->loadEnv();

      $this->servername = getenv('DB_HOST');
      $this->username   = getenv('DB_USERNAME');
      $this->password   = getenv('DB_PASSWORD');
      $this->driver     = getenv('DB_DRIVER');
      $this->dbname     = getenv('DB_NAME');  

      $dns = "mysql:host=".$this->servername.";dbname=".$this->dbname;

      $options =  [
          PDO::ATTR_PERSISTENT   => true,
          PDO::ATTR_ERRMODE      => PDO::ERRMODE_EXCEPTION
      ];

      parent::__construct($dns, $this->username, $this->password, $options);
  }  

    private function loadEnv()
    {
        $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
        $dotenv->load();
    }
}