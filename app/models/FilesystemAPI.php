<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'API.php';
require_once 'Filesystem.php';

class FilesystemAPI extends API{
    
    protected $Filesystem;

    public function __construct($request){
        parent::__construct($request);                       
        $this->Filesystem = new Filesystem('filesystem');
    }
    
    protected function find() {
        $this->Filesystem->setCurrentFolder($_GET['dirname']);
        $this->Filesystem->scan();
        $response = (array) $this->Filesystem->items;
         
        return $response;
     }

     protected function rename(){        
        $this->Filesystem->setCurrentFolder($_POST['dirname']);
        if($this->Filesystem->rename($_POST['old_name'],$_POST['new_name']))
            return array('response'=>true);
        return array('response'=>false);
     }

     protected function delete(){                
        if($this->Filesystem->delete($_POST['dirname'] . '/' .$_POST['name']))
            return array('response'=>true);
        return array('response'=>false);
     }

    protected function createFolder(){                
        if($this->Filesystem->createFolder($_POST['dirname']. '/' .$_POST['name']))
            return array('response'=>true);
        return array('response'=>false);       
     }
     

    protected function uploadFile(){        
         $this->Filesystem->setCurrentFolder($_POST['dirname']);
        if($this->Filesystem->uploadFile('name'))
            return array('response'=>true);
        return array('response'=>false);       
     }
}

