<?php


class sql_connect
{
    public $mysqli = null;

    private $dbHost="localhost";// чаще всего это так, но иногда требуется прописать ip адрес базы данных
    private $dbName='aatk_filebank';// название вашей базы
    private $dbUser='';// пользователь базы данных
    private $dbPass='';// пароль пользователя  
    
    public function __construct() {
        $mysqli = $this->connect();
        $this->mysqli = $mysqli;
    }    
    
    private function connect() {
        if ($_SERVER["SERVER_NAME"] == "localhost") {
            $this->dbHost = "talin.beget.ru";
        }

        $dbHost = $this->dbHost;
        $dbUser = $this->dbUser; 
        $dbPass = $this->dbPass;
        $dbName = $this->dbName;
        
        $mysqli = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }   
        
        if (!mysqli_set_charset($mysqli, "utf8")) {
            printf("Ошибка при загрузке набора символов utf8: %s\n", mysqli_error($mysqli));
        };   
        
        mysqli_query($mysqli, "set names utf8");
        
        return $mysqli;
    }

    public function insert(&$mysqli, $sql) {
        //Выполняем SQL запрос
        if (!$mysqli->ping()) {
            $mysqli = $this->connect();    
        }
        
        if (!mysqli_query($mysqli, $sql)) {
            $error_no = mysqli_errno($mysqli);
            $error_text = mysqli_error($mysqli);
            $sqlstate_text = mysqli_sqlstate($mysqli);
            Die("сдох при инсерт :( - ".$sqlstate_text."  Error: $error_no:".$error_text."  SQL:".$sql);   
        }
        $id = mysqli_insert_id($mysqli);
        return $id;
    }
}

?>