<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

error_reporting(0);

use Demo\DataBaseConnection;
use Demo\DataBaseQuery;
use Mockery;
use PHPUnit_Framework_TestCase;

class ExceptionTest extends PHPUnit_Framework_TestCase
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
        $this->dbModel = new User($this->dbConnMocked);
        $this->dbQuery = new DataBaseQuery($this->dbConnMocked);
        $this->statement = Mockery::mock('\PDOStatement');
    }

    /**
     * @expectedException Demo\FieldUndefinedException
     */
    public function testCreateFieldUndefinedException()
    {
        $this->getTableFields();
        $insertQuery = "INSERT INTO users(name, sex, occupation) VALUES ('Oscar', 'm', 'Software Developer')";
        $this->dbConnMocked->shouldReceive('exec')->with($insertQuery)->andReturn(true);
        $this->dbQuery->create(
                        [
                            'name' => 'Oscar', 
                            'sex' => 'm', 
                            'occupation' => 'Software Developer', 
                            'DOB' => 2005
                        ], 
                        'users', $this->dbConnMocked);
    }

   /**
     * @expectedException Demo\FieldUndefinedException
     */
    public function testUpdateFieldUndefinedException()
    {
        $this->getTableFields();
        $id = 1;
        $data = [
            'DOB'   => '2005',
            'sex' => 'M',
        ];
        $updateQuery = "UPDATE `users` SET `name` = 'Demo',`gender` = 'M' WHERE id = ".$id;
        $this->dbConnMocked->shouldReceive('prepare')->with($updateQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->andReturn(true);
        //$this->dbHandler = new DatabaseHandler('gingers', $this->dbConnMocked);
        $this->dbQuery->update(['id' => $id], $data, 'users', $this->dbConnMocked);
    }

    /**
     * @expectedException Demo\ArgumentNumberIncorrectException
     */
    // public function testIfFindMethodHasAnArgument()
    // {
        
    // }

    /**
     * @expectedException Demo\ArgumentNotFoundException
     */
    // public function testIfFindMethodHasEmptyArgument()
    // {
    //     $id = '';
    //     $sql = 'DELETE FROM users WHERE id = '.$id;
    //     $this->dbConnMocked->shouldReceive('exec')->with($sql)->andReturn(true);
    //     $this->readFromTableHead($id, null);
    //     $bool = User::destroy($id, $this->dbConnMocked);
    //     //$this->assertEquals(true, $bool);
    // }

    /**
     * @expectedException Demo\ArgumentNotFoundException
     */
    // public function testIfDestroyMethodHasAnArgument()
    // {
    //     User::destroy();
    // }

    /**
     * This method checks if the argument passed is an array.
     * 
     */
    public function testArgumentPassedIsArray()
    {
        $this->assertTrue($this->dbModel->checkIfRecordIsEmpty([
            'name'  => 'prosper',
            'alias' => 'gingers',
        ]));
    }

    /**
     * This method checks if the argument passed is empty array.
     */
    public function testEmptyArray()
    {
        $this->assertFalse($this->dbModel->checkIfRecordIsEmpty([]));
    }

    public function getTableFields()
    {
        $fieldName1 = ['Field' => 'name', 'Type' => 'varchar', 'NULL' => 'NO'];
        $fieldName2 = ['Field' => 'sex', 'Type' => 'varchar', 'NULL' => 'NO'];
        $fieldName3 = ['Field' => 'occupation', 'Type' => 'varchar', 'NULL' => 'YES'];

        $fieldName = [$fieldName1, $fieldName2, $fieldName3];

        $this->dbConnMocked->shouldReceive('prepare')->with('SHOW COLUMNS FROM users')->andReturn($this->statement);
        $this->statement->shouldReceive('bindValue')->with(':table', 'users', 2);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($fieldName);

        return $fieldName;
    }
    public function updateRecordHead($id)
    {
        $updateQuery = "UPDATE `users` SET `name` = 'Tope',`sex` = 'M' WHERE id = ".$id;
        $this->dbConnMocked->shouldReceive('prepare')->with($updateQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->andReturn(true);
        $this->dbHandler = new DatabaseHandler('gingers', $this->dbConnMocked);
    }
}
