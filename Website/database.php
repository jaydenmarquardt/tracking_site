<?php
/**
 * This class was made for my convenience doing db calls  *
 */
class Database
{

    private static $connection;

    /**
     * This function connects to the database *
     */
    public static function connect($host, $database, $username, $password){

        self::$connection = new mysqli($host, $username, $password, $database);
        if (self::$connection->connect_error) {
            die("DB Connection failed to connect: " . self::$connection->connect_error);
        }

    }

    /**
     * This function selects rows from the database *
     */
    public static function select($to_select, $table, $where = 0){
        $where = str_replace("\"", "`", $where);
        $sql = "SELECT $to_select FROM `$table` ".($where ? "WHERE $where" : "").";";
        $result = self::$connection->query($sql);
        $rows = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }else{
            return 0;
        }

        return $rows;


    }

    /**
     * This function inserts rows the database *
     */
    public static function insert($table, $fields, $values){

        $sql = "INSERT INTO $table ($fields) VALUES ($values)";

        if ( self::$connection->query($sql) === TRUE) {
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . self::$connection->error;
            die;
            return true;
        }

    }

    /**
     * This function deletes rows from the database *
     */
    public static function delete($table, $where){

        $sql = "DELETE FROM $table WHERE $where";

        if ( self::$connection->query($sql) === TRUE) {
            return true;
        } else {
            echo "Error: " . $sql . "<br>" . self::$connection->error;
            die;
            return true;
        }

    }

    /**
     * This function updates rows in the database *
     */
    public static function update($table, $field, $value, $where){

        $sql = "UPDATE $table SET $field='$value' WHERE $where";

        if ( self::$connection->query($sql) === TRUE) {
           return true;
        } else {
           return false;
        }

    }

    /**
     * This function returns the current connection of the database *
     */
    public static function get()
    {
        return self::$connection;
    }





}