<?php

include_once("pre.php");

class Database
{
    private $db_host = "localhost"; 
    private $db_user = 'root'; 
    private $db_pass = ''; 
    private $db_name = 'greeklife'; 
    private $result = array();
    private $con = false;
    private $myconn; 
    private $column_list;
    private $error;
    private $sql;
    private $bind;
    private $errorCallbackFunction;
    private $errorMsgFormat;
    
    public function getResults() {
        return $this->result;        
    }
    public function getError() {
        echo $this->error;
        return $this->error;
    }

    public function connect() {
         if(!$this->con) {

            try {
                $this->myconn = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
                $this->myconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                $this->error = $e->getMessage();
            } finally {
                if(empty($this->error)) {
                    $this->con = true;
                    return true;
                } else {
                    $this->con = false;
                    return false;
                }
            }
        }
    }
 
    public function disconnect() {
        if($this->con) {
            if(mysqli_close($this->myconn)) {
                $this->con = false; 
                return true; 
            } else {
                return false; 
            }
        }
    }
    public function select($table, $rows = '*', $where = null, $order = null, $values = null, $fetchStyle = null) {
        if($fetchStyle === null) {
            $fetchStyle = PDO::FETCH_ASSOC;
        }
        $q = 'SELECT ' . $rows .' FROM '. $table;
        
        if(isset($this->result)) {
            $this->result = array();
        }

        if($where != null) {
            $q .= ' WHERE '. $where;
        }
        if($order != null) {
            $q .= ' ORDER BY '. $order;
        }
        if($this->tableExists($table)) {
            try {
                $stmt = $this->myconn->prepare($q);
                $stmt->execute($values);
                $this->result = $stmt->fetchAll($fetchStyle);
                if(count($this->result) > 0) {
                    return true;
                } else {
                    return false;
                }
                
            } catch  (PDOException $e) {
                $this->error = $e->getMessage();
                //var_dump($e->getMessage());
                return false;
            } 
        }
    }
  
    public function delete($table, $where = null) {
       if($this->tableExists($table))
        {
            if($where == null) {
                $delete = 'DELETE '.$table; 
            } else {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where; 
            }
            $del = mysql_query($delete);
 
            if($del) {
                return true; 
            } else {
               return false; 
            }
        } else{
            return false; 
        }
    }

    private function tableExists($table) {
        try {
            $result = $this->myconn->query("SELECT 1 FROM $table LIMIT 1");
        } catch (PDOException $e) {
            // We got an exception == table not found
            $this->error = $e->getMessage();
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }

    public function selectColumnNames($table) {
        $resultArray = array();
        $query = "SELECT column_name FROM information_schema.columns WHERE Table_name like '{$table}'";

        $res = $this->myconn->query($query);
        if($res->num_rows > 0) {
            $rows = $res->fetch_all();
            array_shift($rows); //Gets rid of first element, which is the primary key
            $this->column_list = $rows;
        }
    }


    public function insert($sql, $values) {
        try {
            $var = $stmt = $this->myconn->prepare($sql);

            $result = $stmt->execute($values);
            if($result) {
                return true;
            } else {
                //var_dump($stmt->errorInfo());
                $this->error = $e->getMessage();
                return false;
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            //var_dump($e->getMessage());
            return  false;
        }
    }

    public function update($sql, $values) {
        try {
            $var = $stmt = $this->myconn->prepare($sql);
            $result = $stmt->execute($values);
            if($result) {
                return true;
            } else {
                //var_dump($stmt->errorInfo());
                $this->error = $e->getMessage();
                return false;
            }
        } catch (PDOException $e) {
            //var_dump($sql);
            //var_dump($e->getMessage());
            $this->error = $e->getMessage();
            return  false;
        }
    }

    private function filter($table, $info) {
        $driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
        if($driver == 'sqlite') {
            $sql = "PRAGMA table_info('" . $table . "');";
            $key = "name";
        }
        elseif($driver == 'mysql') {
            $sql = "DESCRIBE " . $table . ";";
            $key = "Field";
        }
        else {  
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
            $key = "column_name";
        }   

        if(false !== ($list = $this->run($sql))) {
            $fields = array();
            foreach($list as $record)
                $fields[] = $record[$key];
            return array_values(array_intersect($fields, array_keys($info)));
        }
        return array();
    }

    public function run($sql, $bind="") {
        $this->sql = trim($sql);
        $this->bind = $this->cleanup($bind);
        $this->error = "";

        try {
            $pdostmt = $this->prepare($this->sql);
            if($pdostmt->execute($this->bind) !== false) {
                if(preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql))
                    return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
                elseif(preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $this->sql))
                    return $pdostmt->rowCount();
            }   
        } catch (PDOException $e) {
            $this->error = $e->getMessage();    
            $this->debug();
            return false;
        }
    }
}



