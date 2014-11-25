<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting(0);

require_once 'models/FilesystemAPI.php';

try {

	if(isset($_SERVER["CONTENT_TYPE"]) && stripos($_SERVER["CONTENT_TYPE"], "application/json") === 0) {
		$_POST = $_REQUEST = json_decode(file_get_contents("php://input"), true);
	}

    $API = new FilesystemAPI($_REQUEST);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
