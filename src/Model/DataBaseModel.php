<?php

namespace Demo;

use Demo\DataBaseQuery;
use Doctrine\Common\Inflector\Inflector;
use Demo\Interfaces\DataBaseModelInterface;

abstract class DataBaseModel implements DataBaseModelInterface
{
    protected $tableName;
    protected $dataBaseConnection;
    protected $DataBaseQuery;
    protected $properties;
    protected $arrayField = [];

    public function __construct()
    {
        $this->tableName = self::getClassName();
        $this->DataBaseQuery = new DataBaseQuery(new DataBaseConnection());
        $this->arrayField['id'] = 0;
    }

    public function __set($properties, $values)
    {
        $this->arrayField[$properties] = $values;
    }

    public function __get($properties)
    {
        return $this->arrayField[$properties];
    }

    public function  getAll()
    {
        $sqlData = $this->DataBaseQuery->read($id = false, self::getClassName());

        if (count($sqlData) > 0) {

            return $sqlData;
        }

        throw new NoDataFoundException("There is no data to display");
    }

    public function save()
    {
        
        if($this->arrayField['id']) {
            $sqlData = $this->DataBaseQuery->read($this->arrayField['id'], self::getClassName());
            if ($this->checkIfRecordIsEmpty($sqlData))  {
                $boolCommit = $this->DataBaseQuery->update(['id' => $this->arrayField['id']], $this->arrayField, self::getClassName());
                if ($boolCommit) {
                    return true;
                }
                throw new NoRecordUpdateException("oops, your record did not update succesfully");
            }
            throw new EmptyArrayException("data passed didn't match any record");
        }

        $boolCommit = $this->DataBaseQuery->create($this->arrayField, self::getClassName());
        if ($boolCommit) {
            return true;
        }
        throw new NoRecordCreatedException("oops,your record did not create succesfully");
    }

    public static function findById($id)
    {
        $numArgs = func_num_args();
        if ($numArgs < 0 || $numArgs > 1) {
            throw new ArgumentNumberIncorrect("Please input just one Argument");
        } elseif ($numArgs == '') {
            //throw new ArgumentNotFound Exception("No Argument found, please input an argument");
        }

        $staticFindInstance = new static();
        $staticFindInstance->id = $id == '' ? false : $id;
        return $staticFindInstance;
    }

    public function destroy($id)
    {
        if ($numArgs < 0 || $numArgs > 1) {
            throw new ArgumentNumberIncorrect("Please input just one Argument");
        } elseif ($numArgs == '') {
            //throw new ArgumentNotFound Exception("No Argument found, please input an argument");
        }

        $sqlData = $this->DataBaseQuery->delete($id, self::getClassName());
        return true;
    }

    public static function getClassName()
    {
        $tableName = explode('\\', get_called_class());
        return Inflector::pluralize(strtolower(end($tableName)));
    }

    /**
     * This method check if the argument passed to this function is an array.
     *
     * @param $arrayOfRecord
     *
     * @return bool
     */
    public function checkIfRecordIsEmpty($arrayOfRecord)
    {
        if (count($arrayOfRecord) > 0) {
            return true;
        }
        return false;
    }
}




