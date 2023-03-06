<?php 
    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if (! $userType=='cms')
        header("Location: index.php");

    if(isset( $_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $stmt = $pdo -> prepare('DELETE FROM users WHERE user_id = ?');
        $stmt -> execute( [$user_id] );
        header( "Location: cms-users.php" );
    }
?>