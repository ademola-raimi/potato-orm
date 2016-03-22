<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

error_reporting(0);

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

    /**
     * This function setup is used to create an object of DataBaseQuery.
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
     * @expectedExceptionMessage Oops, DOB is not defined as a field
     * 
     */
    public function testCreateFieldUndefinedException()
    {
        $this->getTableFields();
        $insertQuery = "INSERT INTO users(name, sex, occupation) VALUES ('Oscar', 'm', 'Software Developer')";
        $this->dbConnMocked->shouldReceive('exec')->once()->with($insertQuery)->andReturn(true);
        $this->dbQuery->create(
                        [
                            'name'       => 'Oscar',
                            'sex'        => 'm',
                            'occupation' => 'Software Developer',
                            'DOB'        => 2005,
                        ],
                        'users', $this->dbConnMocked);
    }

    /**
     * @expectedException Demo\FieldUndefinedException
     * @expectedExceptionMessage Oops, DOB is not defined as a field
     *
     */
    public function testUpdateFieldUndefinedException()
    {
        $this->getTableFields();
        $id = 1;
        $data = [
            'DOB'   => '2005',
            'sex'   => 'M',
        ];
        $updateQuery = "UPDATE `users` SET `name` = 'Demo',`sex` = 'M' WHERE id = ".$id;
        $this->dbConnMocked->shouldReceive('prepare')->once()->with($updateQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute')->once()->andReturn(true);
        $this->dbQuery->update(['id' => $id], $data, 'users', $this->dbConnMocked);
    }

    /**
     * This method checks if the argument passed is an array.
     */
    public function testArgumentPassedIsArray()
    {
        $this->assertTrue($this->dbModel->checkIfRecordIsEmpty([
            'name'       => 'prosper',
            'occupation' => 'trainer',
        ]));
    }

    /**
     * This method checks if the argument passed is empty array.
     */
    public function testEmptyArray()
    {
        $this->assertFalse($this->dbModel->checkIfRecordIsEmpty([]));
    }

    /**
     * @expectedException Demo\NoRecordUpdatedException
     * @expectedExceptionMessage oops, your record did not update succesfully
     *
     */
    public function testThrowNoRecordUpdatedException()
    {
        $this->dbModel->throwNoRecordUpdatedException();
    }

    /**
     * @expectedException Demo\EmptyArrayException
     * @expectedExceptionMessage data passed didn't match any record
     *
     */
    public function testThrowEmptyArrayException()
    {
        $this->dbModel->throwEmptyArrayException();
    }

    /**
     * @expectedException Demo\NoDataFoundException
     * @expectedExceptionMessage oops, no data found in the database
     *
     */
    public function testThrowNoDataFoundException()
    {
        $this->dbModel->throwNoDataFoundException();
    }

    /**
     * @expectedException Demo\NoRecordCreatedException
     * @expectedExceptionMessage oops,your record did not create succesfully
     *
     */
    public function testThrowNoRecordCreatedException()
    {
        $this->dbModel->throwNoRecordCreatedException();
    }

     /**
     * @expectedException Demo\DataEmptyException
     * @expectedExceptionMessage oops, no data found in the column
     *
     */
    public function testThrowDataEmptyException()
    {
        $this->dbModel->throwDataEmptyException();
    }

    /**
     * @expectedException Demo\IdNotFoundException
     * @expectedExceptionMessage Oops, the id  is not in the database, try another id
     *
     */
    public function testReadIdNotFoundException()
    {
        $results = [];

        $readQuery = $id  ? 'SELECT * FROM users WHERE id = '.$id : 'SELECT * FROM users';

        $this->dbConnMocked->shouldReceive('prepare')->with($readQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('bindValue')->with(':table', 'users');
        $this->statement->shouldReceive('bindValue')->with(':id', $id);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($results);

        $allData = User::getAll($this->dbConnMocked);
    }

    /**
     * @expectedException Demo\IdNotFoundException
     */
    public function testDeleteIdNotFoundException()
    {
        $results = [];

        $readQuery = 'SELECT * FROM users WHERE id = '.$id;

        $this->dbConnMocked->shouldReceive('prepare')->with($readQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($results);

        $sql = 'DELETE FROM users WHERE id = '.$id;
        $this->dbConnMocked->shouldReceive('exec')->with($sql)->andReturn(true);
        $bool = DataBaseQuery::delete($id, 'users', $this->dbConnMocked);
    }

    /**
     * @expectedException Demo\ArgumentNotFoundException
     */
    public function testfindByIdArgumentNotFoundException()
    {
        User::findById('');
    }

    /**
     * @expectedException Demo\ArgumentNumberIncorrectException
     */
    public function testfindByArgumentNumberIncorrectException()
    {
        User::findById(2, 3);
    }

    /**
     * @expectedException Demo\ArgumentNotFoundException
     */
    public function testDestroyArgumentNotFoundException()
    {
        User::destroy();
    }

    /**
     * @expectedException Demo\ArgumentNumberIncorrectException
     */
    public function testDestroyArgumentNumberIncorrectException()
    {
        User::destroy(2, 3, 5);
    }

    /**
     * This method returns the tablefield to emulate getColumnNames in DataBaseQuery.
     */
    public function getTableFields()
    {
        $fieldName1 = ['Field' => 'name', 'Type' => 'varchar', 'NULL' => 'NO'];
        $fieldName2 = ['Field' => 'sex', 'Type' => 'varchar', 'NULL' => 'NO'];
        $fieldName3 = ['Field' => 'occupation', 'Type' => 'varchar', 'NULL' => 'YES'];

        $fieldName = [$fieldName1, $fieldName2, $fieldName3];

        $this->dbConnMocked->shouldReceive('prepare')->once()->with('SHOW COLUMNS FROM users')->andReturn($this->statement);
        $this->statement->shouldReceive('bindValue')->once()->with(':table', 'users', 2);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->once()->with(2)->andReturn($fieldName);

        return $fieldName;
    }
}
