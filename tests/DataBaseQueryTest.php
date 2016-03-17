<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

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
        $this->dbQuery = new DataBaseQuery($this->dbConnMocked);
        $this->statement = Mockery::mock('\PDOStatement');
    }

    /*
     * To test if an entry can be added to the database.
     */
    public function testCreate()
    {
        $this->getTableFields();
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
        $this->assertEquals($readData, ['0' => ['id'    => $row['id'], 'name'  => $row['name'], 'sex' => $row['sex'], 'occupation' => $row['occupation']]]);
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

    /**
     * To test the if the column names can be retrieved.
     */
    public function testGetColumnNames()
    {
        $fieldName = $this->getTableFields();
        $resultDataSet = $this->dbQuery->getColumnNames('users', $this->dbConnMocked);
        $this->assertEquals(['0' => $fieldName[0]['Field'], '1' => $fieldName[1]['Field'], '2' => $fieldName[2]['Field']], $resultDataSet);
    }

    /**
     * To test the if the table values can be splitted.
     */
    public function testsplitTableField()
    {
        $tableValues = ['name', 'sex', 'occupation'];
        $resultTableField = $this->dbQuery->splitTableField($tableValues);
        $this->assertEquals($resultTableField, "name,sex,occupation");
    }

    /**
     * To test the if the table values can be formated.
     */
    public function testformatTableValues()
    {
        $tableValues = [
                          "0" => "Oscar",                       
                           "1" => "m",                         
                          "2" => "Software Developer"                        
                       ];  
        $resultTableField = $this->dbQuery->splitTableField($tableValues);
        $this->assertEquals($resultTableField, "Oscar,m,Software Developer");
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
}
