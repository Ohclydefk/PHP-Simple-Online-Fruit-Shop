
<?php
session_start();
require_once('database-connection/database-connection.php');
  
class Crud extends DbConnection
{
    public function __construct(){
 
        parent::__construct();
    }
     
    public function read($sql){
 
        $query = $this->connection->query($sql);
         
        if ($query == false) {
            return false;
        } 
         
        $rows = array();
         
        while ($row = $query->fetch_array()) {
            $rows[] = $row;
        }
         
        return $rows;
    }
         
    public function execute($sql){
 
        $query = $this->connection->query($sql);
         
        if ($query == false) {
            return false;
        } else {
            return true;
        }        
    }
     
    public function escape_string($value){
         
        return $this->connection->real_escape_string($value);
    }
}