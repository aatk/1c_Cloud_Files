<?php

class Proxy {

    public $mysqli = null;
    private $Files = null;
    
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        
        $this->Files = new Files($mysqli);
    }   



    private function getlist($metod, $param) {
        $info['parent'] = $param['parent'];
        //print_r($info);
        $list = $this->Files->getlist($info);   
        //print_r($list);
        return $list;
    }
    
    private function getfile($metod, $param) {
        $info['md5'] = $param['md5'];
        $list = $this->Files->getfile($info);   
        return $list;
    }    

    private function OPEN($metod, $param) {
        $result = array();
        //print_r($param);
        $input = $param[q]; 
        $query = explode('/', $input);
        
        if ($query[0] == "getlist") {
            //Получить список Файлов По родителю
            $param['parent'] = $query[1];
            $result = $this->getlist($metod, $param);
        } elseif ($query[0] == "file") {
            //Получить список Файлов По родителю
            $param['md5'] = $query[1];
            $result = $this->getfile($metod, $param);
        }
        
        return $result;
    }    
    
    private function setimage($metod, $param) {
    
        if ($metod == "POST") {
            $decode = $param['decode'];
            $parent = $param['parent'];
            $uploadFile = $_FILES['file'];
            $tmp_name = $uploadFile['tmp_name'];
            
            $name = $uploadFile['name'];
            $name = mb_convert_encoding($name, 'utf-8', 'cp-1251');

            $data_filename = SID;
            $path = PATH;
            $filename = $path.$data_filename;

            if ( !is_uploaded_file($tmp_name) ) {
                $result["error"] = 'Ошибка при загрузке файла '.$name;
            } else {
                //Считываем файл в строку
                $data = file_get_contents($tmp_name);
                if ($decode == "1") { 
                    $data = base64_decode($data);
                }
                
                $md5 = md5($data);
                $filename = $path.$md5;
                //Теперь нормальный файл можно сохранить на диске
                if ( !empty($data) && ($fp = @fopen($filename, 'wb')) ) {
                    @fwrite($fp, $data);
                    @fclose($fp);
                    //rename($filename, $path.$md5);
                } else {
                    $result["error"] = 'Ошибка при записи файла '.$data_filename;
                }
                
                $info['md5']  = $md5;
                $info['name'] = $name;
                $info['parent']  = $parent;
                $info['size'] = filesize($filename);
                $this->Files->saveinfo($info);

                $result['name'] = $info['name'];
                $result['size'] = $info['size'];
                $result['md5']  = $info['md5'];
                $result["message"] = 'Файл - ' . $result['name'] . ' успешно загружен. ';

            }
            
        } elseif ($metod == "PUT") {
            // Получаем содержимое входящего потока
            $content = file_get_contents('php://input');
            // Записываем содержимое потока в файл
            $file = fopen($filename, 'w');
            fwrite($file, $content);
            fclose($file);

            $result["message"] = 'Файл - ' . $filename . ' успешно загружен. ';
        }
        
        return $result;
    }
    
    private function SAVE($metod, $param) {
        
        $input = $param["q"];
        $query = explode('/', $input);

        if ($query[0] == 'setimage') {
            $result = $this->setimage($metod, $param);
        }
       
        return $result;
    }
    
    
    private function delfile($metod, $param) {

        $result = array();
        
        //Удалим из БД
        $info['md5']    = $param['md5'];
        $info['parent'] = $param['parent'];
        $infofiles = $this->Files->getlist($info);
        foreach ($infofiles as $value) {
            //Удалим любое упоминание о файле
            $res = $this->Files->delfile($value);
        }
        unset($info);
        
        $info['md5']    = $param['md5'];
        $list = $this->Files->getlist($info);
        if (count($list) == 0) {
            //Если больше не осталось объектов использующие этот файл, то удаляем его
            $filename = PATH.$info['md5'];
            unlink($filename);
        }
        
        $result['result'] = true;
        return $result;
    }

    private function DEL($metod, $param) {
        
        $input = $param["q"];
        $query = explode('/', $input);

        if ($query[0] == 'file') {
            $param['md5'] = $query[1];
            $result = $this->delfile($metod, $param);
        }
       
        return $result;
    }
    
    public function GET($param) {
        return $this->OPEN("GET", $param);
    }
    public function POST($param) {
        return $this->SAVE("POST", $param);
    }
    public function PUT($param) {
        return $this->SAVE("PUT", $param);
    }
    public function PATCH($param) {
        return $this->SAVE("PATCH", $param);
    }
    public function DELETE($param) {
        return $this->DEL("DELETE", $param);        
    }
}

?>