<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

use Demo\DataBaseQuery;
use Mockery;
use PHPUnit_Framework_TestCase;

class DataBaseModelTest extends PHPUnit_Framework_TestCase
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
     * This method throws an Exception when a record cannot be updated
     * 
     */
    public function testUpdateSave()
    {
        $id = 1;
        $this->getTableFields();
        $this->readFromTableHead($id, $row);
        $this->updateRecordHead($id);
        
        $user = User::findById(1);
        $user->name = 'Demo';
        $user->sex = 'm';
        $this->setExpectedException('Demo\NoRecordUpdatedException');
        
        $bool = $user->save($this->dbConnMocked);
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

    public function updateRecordHead($id)
    {
        $updateQuery = "UPDATE `users` SET `name` = 'Demo',`sex` = 'm' WHERE id = ".$id;
        $this->dbConnMocked->shouldReceive('prepare')->with($updateQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->andReturn(true);
        $this->dbModel = new User('users', $this->dbConnMocked);
    }
}
