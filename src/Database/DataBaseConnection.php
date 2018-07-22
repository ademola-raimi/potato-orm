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
    public $servername;
    public $username;
    public $password;
    public $driver;
    public $dbname;

    /**
     * This is a constructor; a default method that will be called automatically during class instantiation.
     */
    public function __construct($path = null)
    {
        $this->loadEnv($path);

        $this->servername = getenv('DB_HOST');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->driver = getenv('DB_DRIVER');
        $this->dbname = getenv('DB_NAME');

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        ];

        //parent::__construct($this->getDataBaseDriver(), $this->username, $this->password, $options);
    }

    /**
     * This method determines the driver to be used for appropriate database server.
     *
     *
     * @return string dsn
     */
    public function getDataBaseDriver()
    {
        $dns = $this->driver.':host='.$this->servername.';dbname='.$this->dbname;

        return $dns;
    }

    /**
     * Load Dotenv to grant getenv() access to environment variables in .env file.
     */
    public function loadEnv($path = null)
    {
        $envPath = $path == null ? __DIR__.'/../../' : $path;
        $dotenv = new Dotenv($envPath);
        $dotenv->load();
    }
}
