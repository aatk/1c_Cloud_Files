<?php

class ex_class
{
    public $mysqli = null;
 
    public function jsDate($datestr) {
        $format = 'Y-m-d H:i:s';
        $mdate = date_parse_from_format($format, $datestr);
        $jsDate = $mdate['year'].",".($mdate['month']-1).",".$mdate['day'].",".$mdate['hour'].",".$mdate['minute'].",".$mdate['second'];
        return $jsDate;
    }

    public function get_sql_array($sql) {
        $arr = array();
        
        //Это должно помочь восстанавливать соединения если они разорвались
        if (!$this->mysqli->ping()) {
            $SQL = new sql_connect();
            $this->mysqli = $SQL->mysqli;    
        }
        
        $result = mysqli_query($this->mysqli, $sql); 
        //echo $sql;
        if (!$result) { 
            $error_no = mysqli_errno($this->mysqli);
            $error_text = mysqli_error($this->mysqli);
            $sqlstate_text = mysqli_sqlstate($this->mysqli);
            $this->mastdie(0, "сдох при чтении :( - ".$sqlstate_text."  Error: $error_no:".$error_text."  SQL:".$sql);
        }
        
        try{
            while ($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
            
            mysqli_free_result($result);
        } catch (Exception $e) {
            $this->echo_log($e);
        }
        
        return $arr;
    }
    
    public function get_insert_id($sql) {
        $arr = 0;
        
        //Это должно помочь восстанавливать соединения если они разорвались
        if (!$this->mysqli->ping()) {
            $SQL = new sql_connect();
            $this->mysqli = $SQL->mysqli;    
        }
        
        $result = mysqli_query($this->mysqli, $sql); 
        //echo $sql;
        if (!$result) { 
            $error_no = mysqli_errno($this->mysqli);
            $error_text = mysqli_error($this->mysqli);
            $sqlstate_text = mysqli_sqlstate($this->mysqli);
            $this->mastdie(0, "сдох при чтении :( - ".$sqlstate_text."  Error: $error_no:".$error_text."  SQL:".$sql);
        }
        
        try{
            $arr = mysqli_insert_id($this->mysqli);
            
            mysqli_free_result($result);
        } catch (Exception $e) {
            $this->echo_log($e);
        }
        
        return $arr;
    }

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }
    
    public function realstr($str) {
        //return mysqli_real_escape_string($this->mysqli, $str);
        return $this->mysqli->real_escape_string($str);
    }
    
    public function mastdie($code, $error = "") {
        $this->echo_log("ОШИБКА: ".$error);
        throw new MyException($code.":".$error);
    }
    
    public function echo_log($e) {
        ob_start();
        if ((is_array($e)) || (is_object($e))) {
            print_r($e);
        } else {
            echo $e."\r\n";
        }
        $String = ob_get_contents();
        ob_end_clean();
        
        echo $String;
        file_put_contents('dumplog.txt', $String, FILE_APPEND);
    }
    
}

?>