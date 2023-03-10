<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');

    if ($userType!='cms')
        header("Location: index.php");
    
    if (isset($_GET['user_id'])) {
        $forUser=$_GET['user_id'];
    } else {
        $firstUserPDO = $pdo->prepare('SELECT user_id FROM users LIMIT 1');
        $firstUserPDO->execute();
        $forUser = $firstUserPDO->fetch()->user_id;
    }
    
    if (isset($_GET['hotel_id'])) {
        $forHotel=$_GET['hotel_id'];
    } else {
        $firstHotelPDO = $pdo->prepare('SELECT hotel_id FROM hotels LIMIT 1');
        $firstHotelPDO->execute();
        $forHotel = $firstHotelPDO->fetch()->hotel_id;
    }
    
    if (isset($_GET['room_id'])) {
        $forRoom=$_GET['room_id'];
    } else {
        $firstRoomPDO = $pdo->prepare('SELECT room_id FROM rooms WHERE hotel_id = ? LIMIT 1');
        $firstRoomPDO->execute([$forHotel]);
        $forRoom = $firstRoomPDO->fetch()->room_id;
    }
    
    if (isset($_POST['book'])) {
        $booking = new HotelClasses\Booking();
        $booking->setUserId(filter_var($_POST["userName"], FILTER_SANITIZE_STRING));
        $booking->setHotelId(filter_var($_POST["hotelName"], FILTER_SANITIZE_STRING));
        $booking->setRoomId(filter_var($_POST["roomName"], FILTER_SANITIZE_STRING));
        $booking->setStartDate(filter_var($_POST["startDate"], FILTER_SANITIZE_STRING));
        $booking->setEndDate(filter_var($_POST["endDate"], FILTER_SANITIZE_STRING));
        
        $stmt = $pdo->prepare('INSERT INTO bookings(booking_startdate, booking_enddate, user_id, room_id) values (?, ?, ?, ?)');
        $stmt->execute([$booking->getStartDate(), $booking->getEndDate(), $booking->getUserId(), $booking->getRoomId()]);
        // Go to a booking created page
        //header('Location: index.php');
    }
?>

<script language="javascript" type="text/javascript">
    function doReload(userId, hotelId) {
        document.location = 'cms-createbooking.php?user_id=' + userId + '&hotel_id=' + hotelId;
    }
</script>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Register Hotel</div>
        <div class="card-body">
            <form action="cms-createbooking.php?" method="POST">
                <div class="form-group">
                    <label for="userName">User Name</label>
                    <select name="userName">
                        <?php
                            $userStmt =  $pdo -> prepare('SELECT user_id, user_title, user_firstname, user_lastname FROM users');
                            $userStmt->execute();
                            $users = $userStmt->fetchAll();

                            foreach($users as $user_item) {
                                $userOption = new HotelClasses\User();
                                $userOption->setId($user_item->user_id);
                                $userOption->setTitle($user_item->user_title);
                                $userOption->setFirstname($user_item->user_firstname);
                                $userOption->setLastname($user_item->user_lastname);

                                echo '<option value="' . $userOption->getId() . '"';
                                if ($userOption->getId() == $forUser) echo " selected='selected'";
                                echo '">' . $userOption->getId() . " - " . $userOption->getTitle() . " " . $userOption->getFirstname() . " " . $userOption->getLastname() . '</option>';

                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="hotelName">Hotel Name</label>
                    <select name="hotelName" onChange="doReload(<?php echo $forUser ?>, this.value);">
                        <?php
                            $hotelStmt =  $pdo -> prepare('SELECT hotel_id, hotel_name FROM hotels');
                            $hotelStmt->execute();
                            $hotels = $hotelStmt->fetchAll();

                            foreach($hotels as $hotel_item) {
                                $hotelOption = new HotelClasses\Hotel();
                                $hotelOption->setId($hotel_item->hotel_id);
                                $hotelOption->setName($hotel_item->hotel_name);

                                echo '<option value="' . $hotelOption->getId() . '"';
                                if ($hotelOption->getId() == $forHotel) echo " selected='selected'";
                                echo '">' . $hotelOption->getId() . " - " . $hotelOption->getName() . '</option>';

                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="roomName">Room Name/Number</label>
                    <select name="roomName">
                        <?php
                            $roomStmt =  $pdo -> prepare('SELECT room_id, room_label FROM rooms WHERE hotel_id = ?');
                            $roomStmt->execute([$forHotel]);
                            $rooms = $roomStmt->fetchAll();

                            foreach($rooms as $room_item) {
                                $roomOption = new HotelClasses\Room();
                                $roomOption->setId($room_item->room_id);
                                $roomOption->setLabel($room_item->room_label);

                                echo '<option value="' . $roomOption->getId() . '"';
                                if ($roomOption->getId() == $forRoom) echo " selected='selected'";
                                echo '">' . $roomOption->getId() . " - " . $roomOption->getLabel() . '</option>';

                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="startdate">Start Date</label>
                    <input required type="date" name="startDate" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="endDate">End Date</label>
                    <input required type="date" name="endDate" class="form-control" />
                </div>
                <br />
                <div class="form-group">
                    <button name="book" type="submit" class="btn btn-primary">Book</button>
                    <button name="back" type="button" class="btn btn-primary" onclick="history.go(-1);">Back</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>