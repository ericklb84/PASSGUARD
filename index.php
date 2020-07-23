<?php 
define('HTTP_SERVER','http://localhost/175894ad-d364-4daf-a8cd-8d9abb1956ae/controller/controller.php');

$_SESSION['server_url'] = HTTP_SERVER;

header("Location: ".HTTP_SERVER);
