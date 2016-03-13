<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

use Demo\DataBaseConnection;
use Demo\DataBaseModel;
use Demo\DataBaseQuery;
use Mockery;
use PHPUnit_Framework_TestCase;

class DataBaseQueryTest extends PHPUnit_Framework_TestCase
{
    private $setUpConnection;
    private $dbConnMocked;
    private $dbModel;
    private $dbQuery;
    private $statement;

    /*
     * This function setup is used to create an object of DataBaseQuery
     */
    public function setUp()
    {
        $this->dbConnMocked = Mockery::mock('\Demo\DataBaseConnection');
        $this->dbQuery = new DataBaseQuery(new DataBaseConnection());
        $this->statement = Mockery::mock('\PDOStatement');
    }

    /*
     * To test if an entry can be added to the database.
     */
    public function testCreate()
    {
        $insertQuery = "INSERT INTO users(name,sex,occupation) VALUES ('Oscar','m','Software Developer')";
        $this->dbConnMocked->shouldReceive('exec')->with($insertQuery)->andReturn(true);
        $boolCreate = $this->dbQuery->create(['name' => 'Oscar', 'sex' => 'm', 'occupation' => 'Software Developer'], 'users', $this->dbConnMocked);
        $this->assertEquals(true, $boolCreate);
    }

    /*
     * To test if the record can be retrieved from the table.
     */
    public function testRead()
    {
        $id = 3;
        $row = ['id' => 3, 'name' => 'Oscar', 'sex' => 'm', 'occupation' => 'Software Developer'];

        $this->readFromTableHead($id, $row);
        $readData = DataBaseQuery::read($id, 'users', $this->dbConnMocked);
        $this->assertEquals(['0' => ['id'    => $row['id'],'name'  => $row['name'],'sex' => $row['sex'],'occupation' => $row['occupation'],], ], $readData);
    }

    /*
     * To test if an entry can be edited and updated to the database.
     */
    public function testUpdate()
    {
        $id = 1;
        $data = ['name'   => 'Tope', 'occupation' => 'Student'];
        $this->getTableFields();
        $updateQuery = "UPDATE `users` SET `name` = 'Tope',`occupation` = 'Student' WHERE id = ".$id;
        $this->dbConnMocked->shouldReceive('exec')->with($updateQuery)->andReturn(true);
        $boolUpdate = $this->dbQuery->update(['id' => $id], $data, 'users', $this->dbConnMocked);
        $this->assertEquals(true, $boolUpdate);
    }
    
    /*
     * To test if an entry can be deleted to the database.
     */
    public function testDelete()
    {
        $id = 1;
        $sql = 'DELETE FROM users WHERE id = '.$id;
        $this->dbConnMocked->shouldReceive('exec')->with($sql)->andReturn(true);
        $bool = DataBaseQuery::delete($id, 'users', $this->dbConnMocked);
        $this->assertEquals(true, $bool);
    }

    public function readFromTableHead($id, $row)
    {
        $results = [$row];
        $readQuery = $id  ? 'SELECT * FROM users WHERE id = '.$id : 'SELECT * FROM users';
        $this->dbConnMocked->shouldReceive('prepare')->with($readQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($results);
    }

    public function getTableFields()
    {
        $fieldName1 = ['Field' => 'name', 'Type' => 'varchar', 'NULL' => 'NO'];
        $fieldName2 = ['Field' => 'sex', 'Type' => 'varchar', 'NULL' => 'NO'];
        $fieldName3 = ['Field' => 'occupation', 'Type' => 'varchar', 'NULL' => 'YES'];

        $fieldName = [$fieldName1, $fieldName2, $fieldName3];

        $this->dbConnMocked->shouldReceive('prepare')->with('SHOW COLUMNS FROM gingers')->andReturn($this->statement);
        $this->statement->shouldReceive('bindValue')->with(':table', 'gingers', 2);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($fieldName);

        return $fieldName;
    }
}
