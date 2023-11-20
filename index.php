<?php
//header("Location: php/login.php"); die();
//include_once ("controller/login.php"); // must modify this one

require_once "controller/MainController.php";
$controller = new MainController();
$controller->route();

?>


