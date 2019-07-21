<?php
//set_ini('error_log', '/var/www/logs/movie_create/debug.log');
require_once "/var/www/html/action/FileAction.php";

$actionId = isset($_POST["actionId"]) ? $_POST["actionId"] : false;

if($actionId === false)
{
	header('Location:http://localhost:8080/view/error.php');	
}

switch($actionId) {
	case "001":	
		$action = new FileAction();
		$response = $action->execute();	
		break;
	default:
		header('Location: http://localhost:8080/view/error.php');	
	
}

if(count($responce) > 0){
	foreach($response as $msg){
		error_log("[". date('Y-m-d H:i:s') . "]". $msg, 3, "/var/www/logs/error.log");
	}
}else{
	header('Location: http://localhost:8080/view/index.html');	
}
header('Location: http://localhost:8080/view/error.php');	

