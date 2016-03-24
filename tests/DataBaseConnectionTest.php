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
                'DB_USERNAME = homestead',
                'DB_PASSWORD = secret',
                'DB_HOST     = localhost:33060'
            ];

        $fileEnv = fopen($this->dotEnvFile, "a");
        foreach($data as $val) {
            fwrite($fileEnv, $val."\n");
        }
        fclose($fileEnv);
        
        $this->dataBaseConnection = new DataBaseConnection("vfs://home/");
        
    }

    public function testDotEnvLoading()
    {
        $this->assertEquals($this->dataBaseConnection->servername, 'localhost:33060');
        $this->assertEquals($this->dataBaseConnection->driver, 'mysql');
        $this->assertEquals($this->dataBaseConnection->username, 'homestead');
        $this->assertEquals($this->dataBaseConnection->password, 'secret');
        $this->assertEquals($this->dataBaseConnection->dbname, 'potato');
    }

    public function testGetDataBaseDriverForMySQL()
    {
        $driver = $this->dataBaseConnection->driver;
        $result = $this->dataBaseConnection->getDataBaseDriver();
        $this->assertEquals("mysql:host=localhost:33060;dbname=potato", $result);
    }
}

