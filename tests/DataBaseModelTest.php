<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

use Demo\DataBaseQuery;
use Mockery;
use PHPUnit_Framework_TestCase;
use Demo\DataBaseConnection;
use org\bovigo\vfs\vfsStream;
use Dotenv\Dotenv;


class DataBaseModelTest extends PHPUnit_Framework_TestCase
{
    private $setUpConnection;
    private $dbConnMocked;
    private $dbModel;
    private $dbQuery;
    private $statement;
    private $root;
    private $dotEnvFile;
    private $dataBaseConnection;

    /*
     * This function setup is used to create an object of DataBaseQuery
     */
    public function setUp()
    {
        $this->dbConnMocked = Mockery::mock('\Demo\DataBaseConnection');
        $this->dbModel = new User($this->dbConnMocked);
        $this->dbQuery = new DataBaseQuery($this->dbConnMocked);
        $this->statement = Mockery::mock('\PDOStatement');


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
        foreach($data as $d) {
            fwrite($fileEnv, $d."\n");
        }
        fclose($fileEnv);
        $this->dataBaseConnection = new DataBaseConnection($this->dotEnvFile);
        
    }




    /*
     * To test if the whole record can be retrieved.
     */
    public function testGetAll()
    {
        $id = false;
        $this->getTableData();
        $allData = User::getAll($this->dbConnMocked);

        $this->assertEquals($allData, [
                                          '0' => ['id' => 1, 'name' => 'Tope', 'sex' => 'f', 'occupation' => 'Student'],
                                                 ['id' => 2, 'name' => 'Demola', 'sex' => 'm', 'occupation' => 'Software Developer'],
                                                 ['id' => 3, 'name' => 'Tope', 'sex' => 'm', 'occupation' => 'Software Developer'],
                                      ]
                           );
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
    
    public function testGetDataBaseDriverForSQLite()
    {
        $this->dataBaseConnection->driver = "sqlite";
        $result = $this->dataBaseConnection->getDataBaseDriver();
        $this->assertEquals("sqlite:host=localhost:33060;dbname=potato", $result);
    }
    
    public function testGetDataBaseDriverForPostGres()
    {
        $this->dataBaseConnection->driver = "pgsqlsql";
        $result = $this->dataBaseConnection->getDataBaseDriver();
        $this->assertEquals("pgsqlsql:host=localhost:33060;dbname=potato", $result);
    }
    
    /*
     * To test if a record can be deleted.
     */
    public function testDestroy()
    {
        $id = 1;
        $sql = 'DELETE FROM users WHERE id = '.$id;
        $this->dbConnMocked->shouldReceive('exec')->with($sql)->andReturn(true);
        $this->readFromTableHead($id, null);
        $bool = User::destroy($id, $this->dbConnMocked);
        $this->assertEquals(true, $bool);
    }

    /**
     * To test if the tablename is pluralized and converted to lowercase.
     */
    public function testGetClassName()
    {
        $className = $this->dbModel->getClassName();
        $this->assertEquals($className, 'users');
    }

    /**
     * This method returns the row with a particular id.
     */
    public function readFromTableHead($id, $row)
    {
        $results = [$row];
        $readQuery = $id  ? 'SELECT * FROM users WHERE id = '.$id : 'SELECT * FROM users';
        $this->dbConnMocked->shouldReceive('prepare')->with($readQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($results);
    }

    /**
     * This method returns the tablefield to emulate getColumnNames in DataBaseQuery.
     */
    public function getTableFields()
    {
        $fieldName1 = ['Field' => 'id', 'Type' => 'int', 'NULL' => 'NO'];
        $fieldName2 = ['Field' => 'name', 'Type' => 'varchar', 'NULL' => 'NO'];
        $fieldName3 = ['Field' => 'sex', 'Type' => 'varchar', 'NULL' => 'NO'];

        $fieldName = [$fieldName1, $fieldName2, $fieldName3];

        $this->dbConnMocked->shouldReceive('prepare')->once()->with('SHOW COLUMNS FROM users')->andReturn($this->statement);
        $this->statement->shouldReceive('bindValue')->once()->with(':table', 'users', 2);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->once()->with(2)->andReturn($fieldName);

        return $fieldName;
    }

    /**
     * This method returns the tablefield to emulate getColumnNames in DataBaseQuery.
     */
    public function getTableData()
    {
        $row1 = ['id' => 1, 'name' => 'Tope', 'sex' => 'f', 'occupation' => 'Student'];
        $row2 = ['id' => 2, 'name' => 'Demola', 'sex' => 'm', 'occupation' => 'Software Developer'];
        $row3 = ['id' => 3, 'name' => 'Tope', 'sex' => 'm', 'occupation' => 'Software Developer'];

        $results = [$row1, $row2, $row3];

        $readQuery = $id  ? 'SELECT * FROM users WHERE id = '.$id : 'SELECT * FROM users';

        $this->dbConnMocked->shouldReceive('prepare')->with($readQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('bindValue')->with(':table', 'users');
        $this->statement->shouldReceive('bindValue')->with(':id', $id);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($results);

        return $results;
    }
}
