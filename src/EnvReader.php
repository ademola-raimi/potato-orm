<?php

namespace Demo;

use Dotenv\Dotenv;
use PDO;

class EnvReader extends Dotenv
{
    loadEnv();

    $servername = getenv('DB_HOST');
    $username   = getenv('DB_USERNAME');
    $password   = getenv('DB_PASSWORD');
    $driver     = getenv('DB_DRIVER');
    $dbname     = getenv('DB_NAME');

    public function __construct()
    {
        parent::__construct($_SERVER['DOCUMENT_ROOT']);
    }
     
    function loadEnv()
    {
	    return $this->load();
    }
    
}