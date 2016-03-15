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
        $this->dbModel = new User();
        $this->statement = Mockery::mock('\PDOStatement');
    }

    /*
     * To test if the whole record can be retrieved.
     */
    public function testGetAll()
    {
        $id = false;
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

        $allData = User::getAll($this->dbConnMocked);

        $this->assertEquals($allData, [
            '0'   => ['id' => $row1['id'], 'name' => $row1['name'], 'sex' => $row1['sex'], 'occupation' => $row1['occupation']],
            ['id' => $row2['id'], 'name' => $row2['name'], 'sex' => $row2['sex'], 'occupation' => $row2['occupation']],
            ['id' => $row3['id'], 'name' => $row3['name'], 'sex' => $row3['sex'], 'occupation' => $row3['occupation']],
        ]);
    }

    /*
     * To test if it can save(create) a record.
     */
    public function testCreateSave()
    {
        $this->getTableFields();
        $insertQuery = "INSERT INTO users(name,sex,occupation) VALUES ('Oscar','m','Software Developer')";
        $this->dbConnMocked->shouldReceive('exec')->with($insertQuery)->andReturn(true);
        $boolCreate = $this->dbModel->save($this->dbConnMocked);
        $this->assertEquals(true, $boolCreate);
    }

    /*
     * To test if it can save(edit) a record.
     */
    public function testUpdateSave()
    {
        $this->getTableFields();
        $id = 1;

        $data = ['name'   => 'Tope', 'occupation' => 'Student'];
        $this->getTableFields();
        $updateQuery = "UPDATE `users` SET `name` = 'Tope',`occupation` = 'Student' WHERE id = ".$id;
        $this->dbConnMocked->shouldReceive('exec')->with($updateQuery)->andReturn(true);
        $boolCreate = $this->dbModel->save($this->dbConnMocked);
        $this->assertEquals(true, $boolCreate);
    }

    /*
     * To test if a record can be deleted.
     */
    public function testDelete()
    {
        $id = 1;
        $sql = 'DELETE FROM users WHERE id = '.$id;
        $this->dbConnMocked->shouldReceive('exec')->with($sql)->andReturn(true);
        $bool = User::destroy($id, $this->dbConnMocked);
        $this->assertEquals(true, $bool);
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

    public function readFromTableHead($id, $row)
    {
        $results = [$row];
        $readQuery = $id  ? 'SELECT * FROM users WHERE id = '.$id : 'SELECT * FROM users';
        $this->dbConnMocked->shouldReceive('prepare')->with($readQuery)->andReturn($this->statement);
        $this->statement->shouldReceive('execute');
        $this->statement->shouldReceive('fetchAll')->with(2)->andReturn($results);
    }
}
