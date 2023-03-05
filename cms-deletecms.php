<?php 
    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if (! $userType=='cms')
    header("Location: index.php");

    if(isset( $_GET['cms_id'])) {
      $cms_id = $_GET['cms_id'];
      $stmt = $pdo -> prepare('DELETE FROM staff WHERE staff_id = ?');
      $stmt -> execute( [$cms_id] );
      header( "Location: cms-admin.php" );
    }
?>