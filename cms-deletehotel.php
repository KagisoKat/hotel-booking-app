<?php 
    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if (! $userType=='cms')
        header("Location: index.php");

    if(isset( $_GET['hotel_id'])) {
        $hotel_id = $_GET['hotel_id'];
        $stmt = $pdo -> prepare('DELETE FROM hotels WHERE hotel_id = ?');
        $stmt -> execute( [$hotel_id] );
        header( "Location: cms-hotels.php" );
    }
?>