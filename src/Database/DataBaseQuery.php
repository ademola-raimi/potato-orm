<?php

namespace Demo;

use PDO;
use Demo\DataBaseConnection;
use Demo\DataBaseHelper;
use Demo\Interfaces\DataBaseQueryInterface;

class DataBaseQuery implements DataBaseQueryInterface
{
    protected $tableName;
    protected $splitTableField;
    protected $formatTableValues;
    protected $dataBaseConnection;
    

    public function __construct(DataBaseConnection $dataBaseConnection)
    {
        $this->dataBaseConnection = $dataBaseConnection;
    }

    public function create($associativeArray, $tableName)
    {

        $tableFields   = [];
        $tableValues   = [];

        foreach ($associativeArray as $key => $val) {
            $tableFields[] = $key;
            $tableValues[] = $val;
        }
        var_dump($associativeArray['id']);
        $sql = 'INSERT INTO '.$tableName;
        $sql .= '('.$this->splitTableField($tableFields).') ';
        $sql .= 'VALUES ('.$this->formatTableValues($tableValues).')';

        $statement = $this->dataBaseConnection->exec($sql);

        return $statement;
    }

    public function read($id, $tableName)
    {
        $tableData = [];
        $sql = $id ? 'SELECT * FROM '.$tableName.' WHERE id = '.$id : 'SELECT * FROM '.$tableName;
        $statement = $this->dataBaseConnection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $result) {
            array_push($tableData, $result);
        }

         return $tableData;
    }

    public function update($updateParams, $associativeArray, $tableName)
    {
        $sql = '';
        $updateSql = "UPDATE `$tableName` SET ";


        unset($associativeArray['id']);

        $updateSql .= $this->updateArraySql($associativeArray);


        foreach ($updateParams as $field => $value) {
            $updateSql .= " WHERE $field = $value";
        }

        $statement = $this->dataBaseConnection->exec($updateSql);
        
        return $statement ? : false;
    }

    public function delete($id, $tableName)
    {
        $sql = 'DELETE FROM '.$tableName.' WHERE id = '.$id;

        $statement = $this->dataBaseConnection->exec($sql);

        return true;
    }

    public function splitTableField($tableField)
    {
        $splitTableField = implode(",", $tableField);

        return $splitTableField;
    }

    public function formatTableValues($tableValues)
    {
        

        $formattedValues = [];
    
        foreach($tableValues as $key => $value) {
            $formattedValues[] = "'".$value."'";
        }  
    
        $ValueSql = implode(",", $formattedValues);
    
        return $ValueSql;
    }

    public function updateArraySql($array)
    {
        $updatedValues = [];
     
        foreach($array as $key => $val) {
            $updatedValues[] = "`$key` = '$val'";
        }

        $valueSql = implode(",", $updatedValues);

        return $valueSql;        
    }


}