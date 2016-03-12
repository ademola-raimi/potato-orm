<?php

/**
 * This class is involve in connection to the database.
 *
 * @author   Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
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

    /**
     * This is a constructor; a default method  that will be called automatically during class instantiation.
     */
    public function __construct()
    {
        $this->loadEnv();

        $this->servername = getenv('DB_HOST');
        $this->username   = getenv('DB_USERNAME');
        $this->password   = getenv('DB_PASSWORD');
        $this->driver     = getenv('DB_DRIVER');
        $this->dbname     = getenv('DB_NAME');

        $dns = 'mysql:host='.$this->servername.';dbname='.$this->dbname;

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        ];

        parent::__construct($dns, $this->username, $this->password, $options);
    }

    /**
     * Load Dotenv to grant getenv() access to environment variables in .env file.
     */
    private function loadEnv()
    {
        $dotenv = new Dotenv(__DIR__.'/../../');
        $dotenv->load();
    }
}
