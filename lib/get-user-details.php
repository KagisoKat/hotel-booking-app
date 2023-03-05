<?php
    $userType="guest";
    if(isset($_SESSION['userId'])) {
        require_once('./config/db.php');
        if($_SESSION['userType']=="user") {
            $userType="user";

            $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id=?');
            $stmt -> execute([$_SESSION['userId']]);
            $user_item = $stmt->fetch();

            $user = new HotelClasses\User();
            $user->setId($user_item->user_id);
            $user->setTitle($user_item->user_title);
            $user->setFirstName($user_item->user_firstname);
            $user->setLastName($user_item->user_lastname);
            $user->setEmail($user_item->user_email);
            $user->setAddress($user_item->user_address);
            $user->setPasswordHashed($user_item->user_password);
        } elseif ($_SESSION['userType']=="cms") {
            $userType="cms";

            $stmt = $pdo->prepare('SELECT * FROM staff WHERE staff_id=?');
            $stmt -> execute([$_SESSION['userId']]);
            $staff_item = $stmt->fetch();

            $staff = new HotelClasses\Staff();
            $staff->setId($staff_item->staff_id);
            $staff->setFirstName($staff_item->staff_firstname);
            $staff->setLastName($staff_item->staff_lastname);
            $staff->setEmail($staff_item->staff_email);
            $staff->setPasswordHashed($staff_item->staff_password);
         }
    }
?>