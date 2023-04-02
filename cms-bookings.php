<!-- for librarians to login and view books and authors, etc -->

<?php

    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if ($userType!='cms')
        header("Location: index.php");

    if (isset($_POST['search'])) {
        $searchString = "%" . filter_var($_POST["searchText"], FILTER_SANITIZE_STRING) . "%";
        $stmt = $pdo->prepare('SELECT bookings.booking_id, users.user_email, users.user_title, users.user_firstname, users.user_lastname, bookings.booking_startdate, bookings.booking_enddate, hotels.hotel_name, rooms.room_label, rooms.room_price, bookings.booking_status FROM users INNER JOIN bookings ON users.user_id=bookings.user_id INNER JOIN rooms ON rooms.room_id = bookings.room_id INNER JOIN hotels ON rooms.hotel_id = hotels.hotel_id WHERE users.user_email LIKE :ss OR bookings.booking_id LIKE :ss OR users.user_firstname LIKE :ss OR users.user_lastname LIKE :ss');
        $stmt->bindValue(':ss', $searchString);
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare('SELECT bookings.booking_id, users.user_email, users.user_title, users.user_firstname, users.user_lastname, bookings.booking_startdate, bookings.booking_enddate, hotels.hotel_name, rooms.room_label, rooms.room_price, bookings.booking_status FROM users INNER JOIN bookings ON users.user_id=bookings.user_id INNER JOIN rooms ON rooms.room_id = bookings.room_id INNER JOIN hotels ON rooms.hotel_id = hotels.hotel_id');
        $stmt->execute();
    }

    $allBookings = $stmt->fetchAll();
?>
<?php require('./includes/header.html'); ?>
<div class="container">

    <div class="content">
        <form method="post" name="searchForm" action="cms-bookings.php">
            <input type="text" name="searchText" class="form-control mt-2" />
            <button name="search" type="submit" class="btn btn-primary mt-3 mb-2">Search</button>
        </form>
    </div>
    <div>

        <table border="1" width="100%">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Title</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hotel Name</th>
                <th>Room Name/Number</th>
                <th>Nights</th>
                <th>Price</th>
                <th>Status</th>
                <th>Receipt</th>
                <th>Cancel</th>
                <th>Edit</th>
                <th>Delete</th>
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
            echo "<td>" . $oneBooking->getUser()->getEmail() . "</td>";
            echo "<td>" . $oneBooking->getUser()->getTitle() . "</td>";
            echo "<td>" . $oneBooking->getUser()->getFirstName() . "</td>";
            echo "<td>" . $oneBooking->getUser()->getLastName() . "</td>";
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
                echo '<span hidden>Hidden</span>';
            echo '</td>';
            echo '<td><a href="cms-editbooking.php?booking_id=' . $oneBooking->getId() . '">Edit</a></td>';
            echo '<td><a href="cms-deletebooking.php?booking_id=' . $oneBooking->getId() . '">Delete</a></td>';
            echo "</tr>";
        }
        ?>
        </table>
    </div>
</div>

<?php require('./includes/footer.html'); ?>