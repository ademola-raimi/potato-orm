<?php

/**
 * @author: Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Tests;

use PDO;
use Mockery;
use Dotenv\Dotenv;
use Demo\DataBaseQuery;
use Demo\DataBaseModel;
use Demo\DataBaseConnection;
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
        $this->dbConnMocked = Mockery::mock('\Laztopaz\PotatoORM\DatabaseConnection');
        $this->dbQuery = new DataBaseQuery(new DataBaseConnection());
        $this->dbModel = new DataBaseModel('users', $this->dbConnMocked);
        $this->statement = Mockery::mock('\PDOStatement');
    }

    /*
     * To test if an entry can be added to the database.
     */
    public function testCreate()
    {
        $this->splitTableField();
        $this->formatTableValues();
        $boolCreate = $this->dbQuery->create(["name" => "Oscar", "sex" => "m", "occupation" => "Software Developer"], 'users', $this->dbConnMocked);
        $this->assertEquals(true, $boolCreate);
    } 
    
    /*
     * To test if the database can be retrieved from.
     */
    public function testRead()
    {
        $id = 2;
        $row1 = ["name" => "Oscar", "sex" => "m", "occupation" => "Software Developer"];
        $row2 = ["name" => "Tope", "sex" => "m", "occupation" => "Software Developer"];
        $row3 = ["name" => "Unicodevelper", "sex" => "m", "occupation" => "Trainer"];

        $results = [$row1, $row2, $row3];

        $readQuery = $id  ? 'SELECT * FROM users WHERE id = '.$id : 'SELECT * FROM users';
        $this->dbConnMocked->shouldReceive('prepare')->with($readQuery)->andReturn($this->statement);

        $dbEntry = $this->dbQuery->read(2, 'users',$this->dbConnMocked);
        $this->assertEquals(["name" => "Tope", "sex" => "m", "occupation" => "Software Developer"], $dbEntry);
    } 

    /*
     * To test if an entry can be edited and updated to the database.
     */
    public function testUpdate()
    {
        $id = 1;
        $data = [
            'name'   => 'Kola',
            'gender' => 'Male',
        ];
        $this->updateArraySql();
        $boolUpdate = $this->dbQuery->update(['id' => $id], $data, 'users', $this->dbConnMocked);
        $this->assertEquals(false, $boolUpdate);
    }

    /*
     * To test if an entry can be edited and updated to the database.
     */
    public function testDelete()
    {
        $id = 2;
        $row1 = ["name" => "Oscar", "sex" => "m", "occupation" => "Software Developer"];
        $row2 = ["name" => "Tope", "sex" => "m", "occupation" => "Software Developer"];
        $row3 = ["name" => "Unicodevelper", "sex" => "m", "occupation" => "Trainer"];
        $results = [$row1, $row2, $row3];
        $deleteQuery = 'DELETE FROM '.$tableName.' WHERE id = '.$id;
        $this->dbConnMocked->shouldReceive('prepare')->with($deleteQuery)->andReturn($this->statement);

        $dbEntry = $this->dbQuery->delete(2, 'users', $this->dbConnMocked);
        $this->assertEquals(true, $dbEntry);
    }
}
