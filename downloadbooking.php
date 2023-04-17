<?php 
    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    require('./lib/gen-uuid.php'); 

    //if (! $userType=='cms')
    //    header("Location: index.php");

    if (isset($_GET['booking_id'])) {
        $booking_id = $_GET['booking_id'];
        
        $stmt = $pdo->prepare('SELECT bookings.booking_id, users.user_email, users.user_phone, users.user_title, users.user_firstname, users.user_lastname, bookings.booking_startdate, bookings.booking_enddate, hotels.hotel_name, rooms.room_label, rooms.room_price, bookings.booking_status FROM users INNER JOIN bookings ON users.user_id=bookings.user_id INNER JOIN rooms ON rooms.room_id = bookings.room_id INNER JOIN hotels ON rooms.hotel_id = hotels.hotel_id WHERE bookings.booking_id=?');
        $stmt -> execute( [$booking_id] );

        $booking_item = $stmt->fetch();

        $booking = new HotelClasses\Booking();
        $booking->setId($booking_item->booking_id);
        $booking->setUser(new HotelClasses\User());
        $booking->getUser()->setEmail($booking_item->user_email);
        $booking->getUser()->setTitle($booking_item->user_title);
        $booking->getUser()->setFirstName($booking_item->user_firstname);
        $booking->getUser()->setLastName($booking_item->user_lastname);
        $booking->getUser()->setPhone($booking_item->user_phone);
        $booking->setStartDate($booking_item->booking_startdate);
        $booking->setEndDate($booking_item->booking_enddate);
        $booking->setStatus($booking_item->booking_status);
        $booking->setHotel(new HotelClasses\Hotel);
        $booking->getHotel()->setName($booking_item->hotel_name);
        $booking->setRoom(new HotelClasses\Room);
        $booking->getRoom()->setLabel($booking_item->room_label);
        $booking->getRoom()->setPrice($booking_item->room_price);
        
        $filename = 'receipts/' . genuuid() . ".txt";
        $receipt = fopen($filename, "w") or die('Cannot open file for writing: "$filename". Check access permissions');
        
        fwrite($receipt, "Booking Receipt\n");
        fwrite($receipt, "---------------\n");
        fwrite($receipt, "Booking ID: " . $booking->getId() . "\n");
        fwrite($receipt, "Name: " . $booking->getUser()->getTitle() . " " . $booking->getUser()->getFirstName() . " " . $booking->getUser()->getLastName() . "\n");
        fwrite($receipt, "Email: " . $booking->getUser()->getEmail() . "\n");
        fwrite($receipt, "Phone: " . $booking->getUser()->getPhone() . "\n");
        fwrite($receipt, "Hotel: " . $booking->getHotel()->getName() . "\n");
        fwrite($receipt, "Room: " . $booking->getRoom()->getLabel() . "\n");
        fwrite($receipt, "Start Date: " . $booking->getStartDate() . "\n");
        fwrite($receipt, "End Date: " . $booking->getEndDate() . "\n");
        fwrite($receipt, "No. of Nights: " . $booking->getNights() . "\n");
        fwrite($receipt, "Total: R" . number_format($booking->getPrice(),2) . "\n");
        
        //echo "<pre>";
        //var_dump($booking);
        //echo "</pre>";
        //echo "<pre>Receipt written: " . $filename . "</pre>";

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Prama: public");
        header("Content-Type: text/plain");
        header("Content-Length: " . filesize($filename));
        readfile($filename);
        unlink($filename);

        //echo "Receipt: <a href=\"" . $filename . "\">Link</a>";
        //$status = unlink($filename);
        //if ($status)
        //    echo "File deleted successfully";
        //else
        //    echo "Delete failure";
        //header( "Location: " . $_SERVER['HTTP_REFERER']);
    }
?>