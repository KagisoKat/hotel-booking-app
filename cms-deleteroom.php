<?php 
    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if (! $userType=='cms')
        header("Location: index.php");

    if ((isset($_GET['room_id'])) && isset($_GET['hotel_id'])) {
        $room_id = $_GET['room_id'];
        $hotel_id = $_GET['hotel_id'];
        
        $stmt = $pdo -> prepare('DELETE FROM rooms WHERE room_id = ?');
        $stmt -> execute( [$room_id] );

        header( "Location: cms-edithotel.php?hotel_id=" . $hotel_id );
    }
?>