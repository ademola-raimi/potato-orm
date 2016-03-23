<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

use PHPUnit_Framework_TestCase;
use Demo\DataBaseConnection;
use org\bovigo\vfs\vfsStream;

class DataBaseConnectionTest extends PHPUnit_Framework_TestCase
{
    private $root;
    private $dotEnvFile;
    private $dataBaseConnection;

    /*
     * This function setup is used to create an object of DataBaseQuery
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('home');
        $this->dotEnvFile = vfsStream::url('home/.env');

        $data = [
                'DB_NAME     = potato',
                'DB_DRIVER   = mysql',
                'DB_USERNAME = root',
                'DB_PASSWORD = ',
                'DB_HOST     = 127.0.0.1'
            ];
        $fileEnv = fopen($this->dotEnvFile, "a");
        foreach($data as $d) {
            fwrite($fileEnv, $d."\n");
        }
        fclose($fileEnv);
        
        $this->dataBaseConnection = new DataBaseConnection("vfs://home/");
        
    }

    public function testDotEnvLoading()
    {
        $this->assertEquals($this->dataBaseConnection->servername, '127.0.0.1');
        $this->assertEquals($this->dataBaseConnection->driver, 'mysql');
        $this->assertEquals($this->dataBaseConnection->username, 'root');
        $this->assertEquals($this->dataBaseConnection->password, '');
        $this->assertEquals($this->dataBaseConnection->dbname, 'potato');
    }

    public function testGetDataBaseDriverForMySQL()
    {
        $driver = $this->dataBaseConnection->driver;
        $result = $this->dataBaseConnection->getDataBaseDriver();
        $this->assertEquals("mysql:host=127.0.0.1;dbname=potato", $result);
    }
    
    public function testGetDataBaseDriverForSQLite()
    {
        $this->dataBaseConnection->driver = "sqlite";
        $result = $this->dataBaseConnection->getDataBaseDriver();
        $this->assertEquals("sqlite:host=127.0.0.1;dbname=potato", $result);
    }
    
    public function testGetDataBaseDriverForPostGres()
    {
        $this->dataBaseConnection->driver = "pgsqlsql";
        $result = $this->dataBaseConnection->getDataBaseDriver();
        $this->assertEquals("pgsqlsql:host=127.0.0.1;dbname=potato", $result);
    }
}

