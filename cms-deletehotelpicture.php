<?php 
    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if (! $userType=='cms')
        header("Location: index.php");

    if ((isset($_GET['picture_id'])) && isset($_GET['hotel_id'])) {
        $picture_id = $_GET['picture_id'];
        $hotel_id = $_GET['hotel_id'];
        
        $stmt = $pdo -> prepare('SELECT hp_filename FROM hotel_pictures WHERE hp_id = ?');
        $stmt -> execute( [$picture_id] );
        $filename = $stmt->fetch()->hp_filename;

        $stmt = $pdo -> prepare('DELETE FROM hotel_pictures WHERE hp_id = ?');
        $stmt -> execute( [$picture_id] );
        $status = unlink('hotel-images/' . $filename);
        if ($status)
            echo "File deleted successfully";
        else
            echo "Delete failure";
        header( "Location: cms-edithotel.php?hotel_id=" . $hotel_id );
    }
?>