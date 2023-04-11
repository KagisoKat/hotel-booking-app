<!-- for librarians to login and view books and authors, etc -->

<?php

    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if ($userType!='user')
        header("Location: index.php");

    if (!isset($_GET['sorting']))
        $bookingSorting = 'id';
    else
        $bookingSorting = $_GET['sorting'];

    if (isset($_POST['search'])) {
        $searchString = "%" . filter_var($_POST["searchText"], FILTER_SANITIZE_STRING) . "%";
        $bookingQuery = 'SELECT bookings.booking_id, users.user_email, users.user_title, users.user_firstname, users.user_lastname, bookings.booking_startdate, bookings.booking_enddate, hotels.hotel_name, rooms.room_label, rooms.room_price, bookings.booking_status FROM users INNER JOIN bookings ON users.user_id=bookings.user_id INNER JOIN rooms ON rooms.room_id = bookings.room_id INNER JOIN hotels ON rooms.hotel_id = hotels.hotel_id WHERE (users.user_email LIKE :ss OR bookings.booking_id LIKE :ss OR users.user_firstname LIKE :ss OR users.user_lastname LIKE :ss) AND users.user_id=:userid';
    } else {
        $bookingQuery = 'SELECT bookings.booking_id, users.user_email, users.user_title, users.user_firstname, users.user_lastname, bookings.booking_startdate, bookings.booking_enddate, hotels.hotel_name, rooms.room_label, rooms.room_price, bookings.booking_status FROM users INNER JOIN bookings ON users.user_id=bookings.user_id INNER JOIN rooms ON rooms.room_id = bookings.room_id INNER JOIN hotels ON rooms.hotel_id = hotels.hotel_id WHERE users.user_id=:userid';
    }

    if ($bookingSorting == 'id') {
        $bookingQuery .= " ORDER BY bookings.booking_id";
    } elseif ($bookingSorting == 'hname') {
        $bookingQuery .= " ORDER BY hotels.hotel_name";
    } elseif ($bookingSorting == 'rlabel') {
        $bookingQuery .= " ORDER BY rooms.room_label";
    } elseif ($bookingSorting == 'sdate') {
        $bookingQuery .= " ORDER BY bookings.booking_startdate";
    } elseif ($bookingSorting == 'edate') {
        $bookingQuery .= " ORDER BY bookings.booking_enddate";
    }

    if (isset($_POST['search'])) {
        $stmt = $pdo->prepare($bookingQuery);
        $stmt->bindValue(':ss', $searchString);
        $stmt->bindValue(':userid', $_SESSION['userId']);
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare($bookingQuery);
        $stmt->bindValue(':userid', $_SESSION['userId']);
        $stmt->execute();
    }
    
    $allBookings = $stmt->fetchAll();
?>
<?php require('./includes/header.html'); ?>
<div class="container">

    <div class="content">
        <form method="post" name="searchForm" action="bookings.php?sorting=<?php echo $bookingSorting ?>">
            <input type="text" name="searchText" class="form-control mt-2" />
            <button name="search" type="submit" class="btn btn-primary mt-3 mb-2">Search</button>
        </form>
    </div>
    <div>
        <p>Sorting:
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=id">ID</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=hname">Hotel Name</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=rlabel">Room</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=sdate">Start Date</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=edate">End Date</a>
        </p>
    </div>
    <div>

        <table border="1" width="100%">
            <tr>
                <th>ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hotel Name</th>
                <th>Room Name/Number</th>
                <th>Nights</th>
                <th>Price</th>
                <th>Status</th>
                <th>Receipt</th>
                <th>Cancel</th>
            </tr>


        <?php
        // output data of each row
        foreach ($allBookings as $booking_item) {
            $oneBooking = new HotelClasses\Booking();
            $oneBooking->setId($booking_item->booking_id);
            $oneBooking->setUser(new HotelClasses\User());
            $oneBooking->getUser()->setEmail($booking_item->user_email);
            $oneBooking->getUser()->setTitle($booking_item->user_title);
            $oneBooking->getUser()->setFirstName($booking_item->user_firstname);
            $oneBooking->getUser()->setLastName($booking_item->user_lastname);
            $oneBooking->setStartDate($booking_item->booking_startdate);
            $oneBooking->setEndDate($booking_item->booking_enddate);
            $oneBooking->setSTatus($booking_item->booking_status);
            $oneBooking->setHotel(new HotelClasses\Hotel);
            $oneBooking->getHotel()->setName($booking_item->hotel_name);
            $oneBooking->setRoom(new HotelClasses\Room);
            $oneBooking->getRoom()->setLabel($booking_item->room_label);
            $oneBooking->getRoom()->setPrice($booking_item->room_price);

            echo "<tr>";
            echo "<td>" . $oneBooking->getId() . "</td>";
            echo "<td>" . $oneBooking->getStartDate() . "</td>";
            echo "<td>" . $oneBooking->getEndDate() . "</td>";
            echo "<td>" . $oneBooking->getHotel()->getName() . "</td>";
            echo "<td>" . $oneBooking->getRoom()->getLabel() . "</td>";
            echo "<td>" . $oneBooking->getNights() . "</td>";
            setlocale(LC_MONETARY, "en_ZA");
            echo "<td>R " . number_format($oneBooking->getPrice(), 2) . "</td>";
            echo "<td>" . $oneBooking->getStatus() . "</td>";
            echo '<td><a href="downloadbooking.php?booking_id=' . $oneBooking->getId() . '">Download</a></td>';
            echo '<td>';
            if ($oneBooking->getCancellable())
                echo '<a href="cancelbooking.php?booking_id=' . $oneBooking->getId() . '">Cancel</a>';
            else
                echo 'N/A';
            echo '</td>';
            echo "</tr>";
        }
        ?>
        </table>
    </div>
</div>

<?php require('./includes/footer.html'); ?>