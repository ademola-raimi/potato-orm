<?php

/**
 * This is the interface class.
 *
 * @author   Raimi Ademola <ademola.raimi@andela.com>
 * @copyright: 2016 Andela
 */
namespace Demo;

interface DataBaseModelInterface
{
    /**
     * This method gets all the record from a particular table.
     *
     *
     * @return associative array
     */
    public static function getAll($dbConn);

    /**
     * This method create or update record in a database table.
     *
     *
     * @return true or false;
     */
    public function save();

    /**
     * This method find a record by id.
     *
     * @param $id
     *
     * @return object
     */
    public static function findById($id, $dbConn);

    /**
     * This method find a record by id and get the data.
     *
     * @return object find
     */
    public function getById($dbConn);

    /**
     * This method delete a row from the table by the row id.
     *
     * @param $id
     *
     * @return true
     */
    public static function destroy($id, $dbConn);
}
