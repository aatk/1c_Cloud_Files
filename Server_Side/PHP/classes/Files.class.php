<?php

class Files extends Hub {

    public $mysqli = null;
    
    private $SQL_CREATE_TABLE = 'CREATE TABLE IF NOT EXISTS `filebank` ( `id` INT NOT NULL AUTO_INCREMENT , `MD5` INT NOT NULL , `Name` VARCHAR(150) NOT NULL , `Size` INT NOT NULL , `Parent` INT NOT NULL , PRIMARY KEY (`id`), UNIQUE `md5` (`MD5`)) ENGINE = InnoDB;';
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        
        //$this->get_insert_id( $this->SQL_CREATE_TABLE );
    }   
    
    public function saveinfo ($fileinfo) {
        
        $result = false;
        if (count($this->get_where("filebank", $fileinfo)) == 0) {
            $this->insert("filebank", $fileinfo);
            $result = true;
        }
        
        return $result;
    }

    public function getlist ($info) {
        $result = $this->get_where("filebank", $info);
        return $result;
    }
    
    public function getfile ($info) {
        
        $result = $this->get_where("filebank", $info);
        //return $result;
        if (count($result) > 0) {
            $path = PATH;
            $fsize = $result[0]['size'];
            $fname = $result[0]['name'];
            $fullfname = $path.$result[0]['md5'];
            
            header("Content-Length: $fsize");
            header("Content-Disposition: filename=\"$fname\"");
            header("Content-Type: application/file");
            echo file_get_contents($fullfname);
            exit;
        }
    }
    
    public function delfile ($info) {
        $result = $this->delete("filebank", $info['id']);
        return $result;
    }    
    
}

?>