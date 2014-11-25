<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Filesystem{
    
    public $main_folder;
    public $current_folder;
    public $items;
    
    public function __construct($path){               
        $this->main_folder = str_replace('\\', '/', realpath(dirname(dirname(dirname(__FILE__)))));

        $this->current_folder = $path;        
    }

    public function setCurrentFolder($folder_path){
        $this->current_folder = $this->main_folder . '/' . str_replace($this->main_folder. '/', '', $folder_path );  
    }
    public function scan(){           
        $this->items = $this->iterateDirectory($this->current_folder);
    }
    
    function iterateDirectory($path)
    {
        $result = array();

        $cdir = scandir($path);
        chmod ( $path , 777 );
        foreach ($cdir as $key => $value){            
             if (!in_array($value,array(".",".."))) 
             {
                if (is_dir($path . DIRECTORY_SEPARATOR . $value)) 
                {    
                    $result[] = array('nodes' => $this->iterateDirectory($path . DIRECTORY_SEPARATOR . $value),
                                        'label'=>$value,
                                        'path' => str_replace('\\', '/', $path),
                                        'is_dir' => true);
                   
                }
                else
                {
                    $result[] = array('label'=>$value,
                                        'path' => str_replace('\\', '/', $path),
                                        'is_dir' => false);
                }
            }
        }  
      
        if(!is_array($result))
            return array('label'=>$result,
                        'path' => str_replace('\\', '/', $path),
                        'is_dir' => false);

        return $result;       
    }

    //not in use at the moment.
    public function write($filename, $data){
        file_put_contents($this->current_folder . $filename, $data);
    }
    
    //TODO implement this request
    public function uploadFile($field_name){        
        
        if (!empty($_FILES[$field_name]["name"])) {
            $tmp_name = $_FILES[$field_name]["tmp_name"];
            $name = $_FILES[$field_name]["name"];
            return move_uploaded_file($tmp_name, "$this->current_folder/$name");
        }
        
        return false;
    }
    
    public function createFolder($folder_name){
        
        return mkdir($folder_name,0777);
    }
    
    public function delete($item_path){
        $bool = false;
        try{
            if(is_file($item_path)){
                $bool = unlink($item_path);
            }elseif(is_dir($item_path)){
                $bool = $this->rrmdir($item_path);
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $bool;
    }
    /*recursively delete a directory that is not empty*/
    public function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            return rmdir($dir);
        }
    }

    public function rename($old_item_path, $new_item_path){
        return rename($this->current_folder.'/'.$old_item_path, $this->current_folder.'/'.$new_item_path);
    }
    
}

