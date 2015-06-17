<?php

///////////////////////////////////////////////////////////////////////////
//
//                      ОБЩИЙ КЛАСС ДЛЯ РАБОТЫ С ХАБОМ
//
///////////////////////////////////////////////////////////////////////////

class Hub extends ex_class {

    
    
    public function get_by_id($Reference="", $id = 0) {
        $result = array();
        if (($id != 0) && ($Reference != "")) {
            //$name = $this->get_name($Reference);
            $name = $Reference;
            $sql = "SELECT * FROM `$name` WHERE `$name`.`id` = $id";
            $array1 = $this->get_sql_array($sql);
            if (count($array1) != 0) {
                $result = $array1;//[0];
            }
        }  
        return $result;
    }

    public function get_by_analogcode($Reference="", $analogname="", $id = 0) {
        $result = array();
        if (($id != 0) && ($analogname != "") && ($Reference != ""))  {
            //$name = $this->get_name($Reference);
            $name = $Reference;
            $sql = "SELECT * FROM `$name` WHERE `$name`.`$analogname` = '$id'";
            $array1 = $this->get_sql_array($sql);
            if (count($array1) != 0) {
                $result = $array1;//[0];
            }
        }  
        return $result;
    }
    
    public function get_like_name($Reference="", $name = "") {
        $result = array();
        if (($name != "") && ($Reference != "")) {
            //$Reference = $this->get_name($Reference);
            $name = $this->mysqli->real_escape_string($name);
            $sql = "SELECT * FROM `$Reference` WHERE `$Reference`.`searchname` LIKE '%$name%'";
            $array1 = $this->get_sql_array($sql);
            if (count($array1) != 0) {
                $result = $array1;//[0];
            }
        }  
        return $result;
    }

    /**
     * @param string $Reference
     * @param $where
     * @return array
     */
    public function get_where($Reference="", $where) {
        $result = array();
        if ($where != 0)  {
            //$Reference = $this->get_name($Reference);
            $VAL = "true";
            if (count($where)>0) {
                $W = array();
                foreach($where as $key => $value) {
                    $W[] = "`$Reference`.`$key` =  '$value'";
                }
                $VAL = implode(" AND ",$W);
            }
            
            $sql = "SELECT * FROM `$Reference` WHERE $VAL";//`pricehotels`.`id` = $id";
            $array1 = $this->get_sql_array($sql);
            if (count($array1) != 0) {
                $result = $array1;
            }
        }  
        return $result;
    }

    /**
     * @param string $Reference
     * @param $info
     * @return bool|int|string
     */
    public function insert($Reference="", $info) {
        $result = false; 
        if (($info != 0) && (is_array($info)) && ($Reference != "")) {
            $keystr = "";
            $paramstr = "";
            foreach ($info as $key => $value) {
                if ($keystr != "") {$keystr = $keystr.', '; };
                if ($paramstr != "") {$paramstr = $paramstr.', '; };
                $keystr = $keystr.'`'.$key.'`';
                //$value = $this->mysqli->real_escape_string($value);
                //$value = addslashes($value);
                $value = $this->realstr($value);
                //$value = $this->mysqli->real_escape_string($value);
                $paramstr = $paramstr."'".$value."'";
            }
            
            $sql="INSERT INTO `$Reference` ($keystr) VALUES ($paramstr)";
            $id = $this->get_insert_id($sql);
            $result = $id;
        }
        return $result;
    }

    /**
     * @param string $Reference
     * @param int $id
     * @param array $info
     * @return bool
     */
    public function update($Reference="", $id = 0, $info) {
        $result = false; 
        if (($info != 0) && (is_array($info)) && ($Reference != "")) {
            $setstr = "";
            foreach ($info as $key => $value) {
                if ($setstr != "") {$setstr = $setstr.', '; };
                //$value = $this->mysqli->real_escape_string($value);
                //$value = addslashes($value);
                $value = $this->realstr($value);
                if (!is_numeric($value)) {$value = "'".$value."'";} 
                else {
                    //print_r(intval($value)." -- ".(floatval($value)+0.2));
                    if (intval($value) != floatval($value)) {$value = "'".$value."'";} else 
                    {$value = intval($value);}
                }
                //if (is_numeric($value) && is_float($value)) {$value = "'".$value."'";};
                $setstr = $setstr."`".$key."` = $value ";
            }
            
            $sql="UPDATE `$Reference` SET $setstr WHERE `id` = $id";
            $this->get_insert_id($sql);
            $result = true;
        }
        return $result;
    }


    public function delete($Reference="",$id = 0) {
        $result = false; 
        if (($id != 0) && ($Reference != "")) {
            $sql="DELETE FROM `$Reference` WHERE `id` = $id";
            $this->get_insert_id($sql);
            $result = true;
        }
        return $result;
    }

    public function set_analog_code($Reference="", $id, $code, $system) {
        $tabename = $Reference;
        $tableid  = $id;
        $system = $system;
        $analogcode = $code;
        
        $sql = "SELECT `analogcode` FROM `analogcode` WHERE `tabename` = '$tabename' AND `tableid`= $tableid AND `system`='$system' ";
        $arr = $this->get_sql_array($sql);
        if (count($arr)>0) {
            if ($arr[0]['analogcode'] != $analogcode) {
                //UPDATE
                $sql = "UPDATE `analogcode`
                            SET
                            `analogcode` = '$analogcode'
                        WHERE
                            `tabename` = '$tabename' AND `tableid` = $tableid AND `system` = '$system'";
                $this->get_insert_id($sql);
            }
        } else {
            //INSERT
            $sql = "INSERT INTO `analogcode`
                    (`tabename`,`tableid`,`system`,`analogcode`)
                    VALUES
                    ('".$Reference."',".$id.",'".$system."','".$code."')";
            $this->get_insert_id($sql);
        }
        
    }


}

?>