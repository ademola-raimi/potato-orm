<?php

namespace Demo;

use PDO;

class DataBaseQuery
{
    protected $properties;
    protected $values;
    protected $tableName;
    protected $dataBaseModel;
    protected $dataBaseConnection;
    protected $splitTableField;
    protected $formatTableValues;

    //protected $updateParams = [];

    
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

        $sql = 'INSERT INTO '.$tableName;
        $sql .= '('.$this->splitTableField($tableFields).') ';
        $sql .= 'VALUES ('.$this->formatTableValues($tableValues).')';

        $bool = $this->dataBaseConnection->exec($sql);

        return $bool;
    }

    public function read($id, $tableName)
    {
        $tableData = [];
        $sql = 'SELECT * FROM '.$tableName.' WHERE id = '.$id;

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


        $updateSql .= $this->updateArraySql($associativeArray);


        foreach ($updateParams as $field => $value) {
            $updateSql .= " WHERE $field = $value";
        }

        $statement = $this->dataBaseConnection->exec($updateSql);
        
        return true;
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