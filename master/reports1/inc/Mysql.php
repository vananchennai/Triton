<?php

include('../../db_connection.php');
include('../../../db_connection.php');
 
class Mysql
{
    /****************************************
     * function __construct()
     ****************************************/
    public function __construct() 
    {
        mysql_connect(DBHOST,DBUSER,DBPASS) or die( mysql_error() ); 
            //     This says connect to the database or exit and
            //    give me the reason I couldn't connect.
        mysql_select_db(DBNAME) or die( mysql_error() );
            //    This will select the db you require or exit and
            //    give a reason why it couldn't
    }
    
    //     Now we'll create a few functions that will help us retreive
    //    information from the database.
        
    /****************************************
     * function select()
     * params - $fields = The fields you want to have in the array.
     *          - $table = The table you want to return information from.
     *          - $where = This allows you to pick with database records you want to return.
     *          - $orderby = This determinds the return order of the select.
     *          - $limit = this will control the amount of records the select returns.    
     ****************************************/
    public function select($fields,$table,$where='',$orderby='',$limit='')
    {
        if($where != '') $where = " WHERE $where";
            //     If a $where variable is pass into the
            //    function set the $where variable.
        if($orderby != '') $orderby = " ORDER BY $orderby";
            //     If a $orderby variable is pass into the
            //    function set the $orderby variable.
        if($limit != '') $limit = " LIMIT $limit";
            //     If a $limit variable is pass into the
            //    function set the $limit variable.

        $recordSet =
            mysql_query(
                "SELECT $fields FROM $table" . $where . $orderby . $limit // Set the SELECT for the query
            ) or die(
                "Selecting $table - SELECT $fields FROM $table"  . $where . $orderby . $limit .
                    " - " . mysql_error()
                    //     If the query fails, we'll exit the function and
                    //    print this string to the screen.
            );
        if (!$recordSet) // A quick check to see if the query failed. This is a backup to the previos die()
        {
            return "Record Set Error";
        }
        else
        {
            $recordSet = new MysqlRecordSet( $recordSet );
                //    MysqlRecordSet lets you control the query resource a better. I'll explain it later in
                //    this code section.        
                
        }
        return $recordSet; // Return the $recordSet whether it passed or now.
    }
    
    //     The rest of the functions work very similiar and are coded the same way. If you have any questions
    //    about them leave a comment and I'll get with you about them.
        
    public function insert($table, $fields, $where='')
    {
        if($where != '') $where = " WHERE $where";
        
        $query =
            mysql_query(
                "INSERT INTO $table SET $fields" . $where
            ) or die(
                "Insert Error - INSERT INTO $table SET $fields" . $where . " - " . mysql_error()
            );
        if($query)
        {
            return true;
        }
        return false;
    }
    public function update($table,$fields,$where='')
    {
        if($where != '') $where = " WHERE $where";
        
        $query =
            mysql_query(
                "UPDATE $table SET $fields" . $where
            ) or die(
                "Update Error - UPDATE $table SET $fields" . $where . " - " . mysql_error()
            );
        if($query)
        {
            return true;
        }
        return false;
    }
    public function delete($table,$where)
    {
        $query =
            mysql_query(
                "DELETE FROM $table WHERE $where"
            ) or die(
                "Delete Error - DELETE FROM $table WHERE $where" . " - " . mysql_error()
            );
        if($query)
        {
            return true;
        }
        return false;
    }
    // This function gets the last mysql insert Id.
    public static function getInsertId()
    {
        return mysql_insert_id();
    }
    
}
class MysqlRecordSet // Allows you to utilize the resourses of the returned mysql_query().
{
    var $recordSet;
    function MysqlRecordSet( &$recordSet ) { $this->recordSet = $recordSet; return; }
   // function __construct( &$recordSet ) { $this->recordSet = $recordSet; return; }
    function getRecordCount() { return mysql_num_rows($this->recordSet); }
        // Returns the record count
    function seek( $recordIndex ) {    return mysql_data_seek( $this->recordSet, $recordIndex ); }
        // Seek to a specific record
    function getFirstRecord() {
        mysql_data_seek( $this->recordSet, 0 );
        return mysql_fetch_array( $this->recordSet ); }
        // Seek to the first Record
    function getNextRecord() { return mysql_fetch_array( $this->recordSet ); }
        // Go to the next record
    function getLastRecord() {
        mysql_data_seek( $this->recordSet, mysql_num_rows($this->recordSet)-1 );
        return mysql_fetch_array( $this->recordSet ); }
        // Seek to the last record
    function free() { return mysql_free_result( $this->recordSet ); }
        // free the result.
}


?>