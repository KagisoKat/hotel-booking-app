<?php
    spl_autoload_register( function($class) {
        $classSplit=explode('\\', $class);
        $path = './classes/';
        require_once  $path . $classSplit[0] . '/' . $classSplit[1] .'.php';
    });
?>