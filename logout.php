<?php
session_start();

require_once('./lib/autoloader.php');

var_dump($_SESSION);

if(isset($_SESSION['userId'])) {
    session_destroy();
    header('Location: index.php');
}

?>