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
        if(!count($response))
            return array('response'=>false, 'errors' => array('No directories or files found...'));
        return $response;
     }

     protected function rename(){
         $errors = array();
        if(empty($_POST['dirname']))
            $errors[] = 'The directory name is empty.';
         if(empty($_POST['old_name']))
             $errors[] = 'The old name is empty.';
         if(empty($_POST['new_name']))
             $errors[] = 'The new name is empty.';
         if(!count($errors)){
            $this->Filesystem->setCurrentFolder($_POST['dirname']);
            if($this->Filesystem->rename($_POST['old_name'],$_POST['new_name']))
                return array('response'=>true);
         }
        return array('response'=>false, 'errors' => $errors);
     }

     protected function delete(){
         $errors = array();
         if(empty($_POST['dirname']))
             $errors[] = 'The directory name is empty.';
         if(empty($_POST['name']))
             $errors[] = 'The item name is empty.';
         if(!count($errors)){
            if($this->Filesystem->delete($_POST['dirname'] . '/' .$_POST['name']))
                return array('response'=>true);
         }
         return array('response'=>false, 'errors' => $errors);
     }

    protected function createFolder(){
        $errors = array();
        if(empty($_POST['dirname']))
            $errors[] = 'The directory name is empty.';
        if(empty($_POST['name']))
            $errors[] = 'The new directory name is empty.';
        if(!count($errors)){
            if($this->Filesystem->createFolder($_POST['dirname']. '/' .$_POST['name']))
                return array('response'=>true);
        }
        return array('response'=>false, 'errors' => $errors);
     }
     

    protected function uploadFile(){
        $errors = array();
        if(empty($_POST['dirname']))
            $errors[] = 'The directory name is empty.';
        if(empty($_FILES['name']["name"]))
            $errors[] = 'The file is missing.';
        if(!count($errors)){
             $this->Filesystem->setCurrentFolder($_POST['dirname']);
            if($this->Filesystem->uploadFile('name'))
                return array('response'=>true);
        }
        return array('response'=>false, 'errors' => $errors);
     }
}

