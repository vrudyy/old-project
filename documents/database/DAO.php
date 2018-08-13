<?php

require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Client.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Role.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Contact.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Language.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Branch.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Room.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Period.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/SchoolYear.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/User.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Note.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Course.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/File.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Qualification.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Exam.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/TutorStatus.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Tutor.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/ProspectStatus.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/ReminderStatus.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/MarketingChannel.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/EducationLevel.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Prospect.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Address.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Student.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Reminder.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Currency.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/TextTemplate.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/Invoice.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/inClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/StudentClass.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/SoW.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/ClassRecord.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/ClassStudent.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/StudentTest.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/database/entities/StudentExamPerformance.php");


final class DAO{
    
    private $connection;
    private $host = "localhost";
    private $username = "volodymyr";
    private $password = "EpjuT4CX8F4hBUvp";
    private $database = "i-nucleus";
    
    function __construct(){
        if(strcmp("C:/xampp/htdocs", $_SERVER['DOCUMENT_ROOT']) == 0){
            $this->connection = mysqli_connect($this->host, "root", "", $this->database);
        }else{
            $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        }
    }
    
    public function next($entityName, $idName){
        $count = 0;
        $sql = "SELECT COUNT(`$idName`) FROM `$entityName`";
        $result = mysqli_query($this->connection, $sql);
        if($result){
            $row = mysqli_fetch_assoc($result);
            $count = $row["COUNT(`$idName`)"] + 1;
        }
        return $count;
    }
    
    public function add($entity, $test = 0){
        $array = json_decode(json_encode($entity), true);
        $className = get_class($entity);
        $sql = "INSERT INTO `$className` ";
        $columns = "(";
        $values = "(";
        $mainId = lcfirst($className."Id");
        foreach($array as $propertyName => $propertyValue){
            if(strcmp($mainId, $propertyName) != 0){
                $columns .= "`$propertyName`,";
                $values  .= (is_int($propertyValue) ) ? "$propertyValue," : "\"$propertyValue\",";
            }  
        }
        $columns = substr($columns, 0, strlen($columns)-1) . ")";
        $values = substr($values, 0, strlen($values)-1) . ")";
        $sql .= $columns . " VALUES " . $values . ";";
        if($test == 1){
            return $sql;
        }
        $this->connection->query($sql);
        
        $sql = "SELECT `$mainId` FROM `$className` ORDER BY `$mainId` DESC LIMIT 1;";
        $result = mysqli_query($this->connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $id = $row[$mainId];
        return $id;
    }
    
    
    public function get($entityName, $value, $column, $test = 1) {
        $sql = (is_int($value)) ? "SELECT * FROM `$entityName` WHERE `$column` = $value" : "SELECT * FROM `$entityName` WHERE `$column` = '$value'";
        $result = mysqli_query($this->connection, $sql);
        if($result){
            $entityName = new $entityName();
            $array = json_decode(json_encode($entityName), true);
            $row = mysqli_fetch_assoc($result);
            foreach($array as $property => $value){
                $entityName->$property = $row[$property];
            }
        }
        return ($test == 1) ? $entityName : $sql;
    }
    
    public function getWhere($entityName, $where, $test = 1){
        $sql =  "SELECT * FROM `$entityName` $where";
        $result = mysqli_query($this->connection, $sql);
        if($result){
            $entityName = new $entityName();
            $array = json_decode(json_encode($entityName), true);
            $row = mysqli_fetch_assoc($result);
            foreach($array as $property => $value){
                $entityName->$property = $row[$property];
            }
        }
        return ($test == 1) ? $entityName : $sql;
    }

    public function listAll($entityName, $whereColumn = "", $whereValue="", $sort="", $sortColoumn="") {
        $entityName = $entityName;
        $sql = "SELECT * FROM `$entityName`";
        if(strlen($whereColumn) != 0 && strlen($whereValue) != 0){
            $sql .= " WHERE `$whereColumn` = ";
            $sql .= (is_int($whereValue)) ? $whereValue : "\"$whereValue\"";
        }
        
        if(strlen($sort) != 0){
            $sql .= " ORDER BY ".$sortColoumn." ".$sort;
        }
        
        $list = [];
        $result = $this->connection->query($sql);
        while($row = $result->fetch_assoc()) {
            $entityName = new $entityName();
            $array = json_decode(json_encode($entityName), true);
            foreach($array as $property => $value){
                $entityName->$property = $row[$property];
            }
            array_push($list, $entityName);
        }
        return $list;
    }
    
    public function listAllWhere($entityName, $where){
        $entityName = $entityName;
        $sql = "SELECT * FROM `$entityName`". " $where";
        
        
        $list = [];
        $result = $this->connection->query($sql);
        while($row = $result->fetch_assoc()) {
            $entityName = new $entityName();
            $array = json_decode(json_encode($entityName), true);
            foreach($array as $property => $value){
                $entityName->$property = $row[$property];
            }
            array_push($list, $entityName);
        }
        return $list;
    }

    public function update($entity) {
        $sql = "UPDATE `". get_class($entity)."` SET ";
        $array = json_decode(json_encode($entity), true);
        foreach($array as $propertyName => $propertyValue){
            $sql .= (is_int($propertyValue)) ? "`$propertyName`=$propertyValue, " :"`$propertyName`='$propertyValue', ";
        }
        $sql = substr($sql, 0, strlen($sql)-2);
        reset($array);
        $sql .= " WHERE ".key($array)." = ".reset($array).";";
        $this->connection->query($sql);
    }
    
    public final function delete($entityName, $whereColumn = "", $whereValue=""){
        $sql = (is_int($whereValue))? "DELETE FROM `".$entityName."` WHERE `".$whereColumn."` = \"".$whereValue."\";" :"DELETE FROM `".$entityName."` WHERE `".$whereColumn."` = ".$whereValue.";";
        $this->connection->query($sql);
    }
    
    public final function deleteWhere($entityName, $where){
        $sql = 'DELETE FROM `'.$entityName.'` '.$where.';';
        $this->connection->query($sql);
    }
    
    public final function close(){
        $this->connection->close();
    }
}
