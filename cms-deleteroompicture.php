<?php 
    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if (! $userType=='cms')
        header("Location: index.php");

    if ((isset($_GET['picture_id'])) && isset($_GET['hotel_id']) && isset($_GET['room_id'])) {
        $picture_id = $_GET['picture_id'];
        $hotel_id = $_GET['hotel_id'];
        $room_id = $_GET['room_id'];
        
        $stmt = $pdo -> prepare('SELECT rp_filename FROM room_pictures WHERE rp_id = ?');
        $stmt -> execute( [$picture_id] );
        $filename = $stmt->fetch()->rp_filename;

        $stmt = $pdo -> prepare('DELETE FROM room_pictures WHERE rp_id = ?');
        $stmt -> execute( [$picture_id] );
        $status = unlink('hotel-images/' . $filename);
        if ($status)
            echo "File deleted successfully";
        else
            echo "Delete failure";
        header( "Location: cms-editroom.php?room_id=" . $room_id . "&hotel_id=" . $hotel_id );
    }
?>