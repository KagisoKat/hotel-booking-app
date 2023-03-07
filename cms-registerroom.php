<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');

    if ($userType!='cms')
        header("Location: index.php");
    
    if (isset($_GET['hotel_id']))
        $hotelId = $_GET['hotel_id'];
    else
        header("Location: cms-hotels.php");
    
    if (isset($_POST['register'])) {
        $room = new HotelClasses\Room();
        $room->setLabel(filter_var($_POST["roomName"], FILTER_SANITIZE_STRING));
        $room->setPrice(filter_var($_POST["roomPrice"], FILTER_SANITIZE_NUMBER_FLOAT));
        $room->setHotelId($hotelId);
        
        $stmt = $pdo->prepare('INSERT INTO rooms (room_label, room_price, hotel_id) VALUES (?, ?, ?)');
       $stmt->execute([$room->getLabel(), $room->getPrice(), $room->getHotelId()]);
        header('Location: cms-edithotel.php?hotel_id=' . $hotelId);
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Register Hotel</div>
        <div class="card-body">
            <form action="cms-registerroom.php?hotel_id=<?php echo $hotelId ?>" method="POST">
                <div class="form-group">
                    <label for="roomName">Room Name</label>
                    <input required type="text" name="roomName" class="form-control" />
                </div>
                <br />
                <div class="form-group">
                    <label for="roomPrice">Room Price</label>
                    <input required type="number" name="roomPrice" class="form-control" />
                </div>
                <br />
                <div class="form-group">
                    <button name="register" type="submit" class="btn btn-primary">Register Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>