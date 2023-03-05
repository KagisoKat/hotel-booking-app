<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpassword = '';
    $dbname = 'hotel';

    //set DSN - database source name
    $dsn = 'mysql:host=' . $dbhost .'; dbname=' . $dbname;

    try {
        // create a PDO instance
        $pdo = new PDO($dsn, $dbuser, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";

    } catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
?>