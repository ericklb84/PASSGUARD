<?php 
define('HTTP_SERVER','http://localhost/~erick/pass/controller/controller.php');

$_SESSION['server_url'] = HTTP_SERVER;

header("Location: ".HTTP_SERVER);
