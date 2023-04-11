<?php
    require_once('./lib/autoloader.php');
    session_start();

    require('./lib/get-user-details.php');
    if (isset($_SESSION['userType'])) $userType=$_SESSION['userType'];
    else $userType='guest';

    if (($userType != 'cms') && ($userType != 'user'))
        header("Location: index.php");

    if (!isset($_SESSION['createBooking']))
        header("Location: index.php");


    require('./includes/header.html');
    require_once('./config/db.php');

    $booking = unserialize($_SESSION['createBooking']);

    $stmt = $pdo->prepare('SELECT * FROM hotels WHERE hotels.hotel_id = :ss');
    $stmt->bindValue(':ss', $booking->getHotel()->getId());
    $stmt->execute();
    $hotelRecord = $stmt->fetch();

    $booking->getHotel()->setName($hotelRecord->hotel_name);
    $booking->getHotel()->setAddress($hotelRecord->hotel_address);
    $booking->getHotel()->setRating($hotelRecord->hotel_rating);

    $stmt = $pdo->prepare('SELECT * FROM hotel_pictures WHERE hotel_pictures.hotel_id = :ss');
    $stmt->bindValue(':ss', $booking->getHotel()->getId());
    $stmt->execute();
    $pictureRecords = $stmt->fetchAll();

    foreach($pictureRecords as $pictureRecord) {
        $picture = new HotelClasses\HotelPicture();
        $picture->setId($pictureRecord->hp_id);
        $picture->setFilename($pictureRecord->hp_filename);
        $booking->getHotel()->addPicture($picture);
    }

    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE rooms.room_id = :ss');
    $stmt->bindValue(':ss', $booking->getRoom()->getId());
    $stmt->execute();
    $roomRecord = $stmt->fetch();

    // echo "<pre>Room record";
    // var_dump($roomRecord);
    // echo "</pre>";

    $booking->getRoom()->setLabel($roomRecord->room_label);
    $booking->getRoom()->setPrice($roomRecord->room_price);

    $stmt = $pdo->prepare('SELECT * FROM room_pictures WHERE room_pictures.room_id = :ss');
    $stmt->bindValue(':ss', $booking->getRoom()->getId());
    $stmt->execute();
    $pictureRecords = $stmt->fetchAll();

    foreach($pictureRecords as $pictureRecord) {
        $picture = new HotelClasses\RoomPicture();
        $picture->setId($pictureRecord->rp_id);
        $picture->setFilename($pictureRecord->rp_filename);
        $booking->getRoom()->addPicture($picture);
    }

    if (isset($_POST["confirm"])) {
        $stmt = $pdo->prepare('INSERT INTO bookings(booking_startdate, booking_enddate, user_id, room_id) values (?, ?, ?, ?)');
        $stmt->execute([$booking->getStartDate(), $booking->getEndDate(), $booking->getUser()->getId(), $booking->getRoom()->getId()]);
        unset($_SESSION['createBooking']);
        header("Location: thankyoubooking.php");
    }

    if (isset($_POST["back"])) {
        echo "Referrer: " . $_SERVER['HTTP_REFERER'];
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    // echo "<pre>";
    // echo "hotelRecord:";
    // var_dump($hotelRecord);
    // echo "\nbooking:";
    // var_dump($booking);
    // echo "\nhotel id:";
    // var_dump($booking->getHotel()->getId());
    // echo "\nroom id:";
    // var_dump($booking->getRoom()->getId());
    // echo "</pre>";
?>

<!-- <div class="content">
    <form method="post" name="searchForm" action="cms-users.php">
        <input type="text" name="searchText" class="form-control mt-2" />
        <button name="search" type="submit" class="btn btn-primary mt-3 mb-2">Search</button>
    </form>
</div> -->
<div>
    <div class="container">
        <div class= "card bg-light mb-3">
            <div class="card-header">
                <h5>Booking Comfirmation</h5>
            </div>
            <div class="card-body">
                <h4>You are booking the following:</h4>
            </div>
        </div>
    </div>
</div>

</div>
<div class="row row-cols-md-2">
    <div class="col">
        <div class="card mx-2 my-2">
            <div class="card-header">
                <span>Hotel</span>
            </div>
            <div id="hotelControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner"> <!-- <div class="carousel-inner card-image-top"> -->
                    <?php $numPictures = 0 ?>
                    <?php foreach ($booking->getHotel()->getPictureArray() as $picture) { ?>
                        <?php $numPictures++ ?>
                        <div class="carousel-item<?php if ($numPictures == 1) echo ' active' ?>">
                            <img src="./hotel-images/<?php echo $picture->getFilename() ?>" class="d-block w-100" />
                        </div>
                    <?php } ?>
                </div>
                <a class="carousel-control-prev" href="#hotelControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#hotelControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
             </div>

            <div class="card-body">
                <h5 class="card-title"><?php echo $booking->getHotel()->getName() ?></h5>
                <?php
                    for ($starCount = 0; $starCount < $booking->getHotel()->getRating(); $starCount++) {
                        //echo "\u{2730}";
                        echo "\u{272D}";
                    } 
                ?>
                <p class="card-text"><?php echo $booking->getHotel()->getAddress() ?></p>
            </div>
        </div>
    </div>
    
    <div class="col">
        <div class="card mx-2 my-2">
            <div class="card-header">
                <span>Room</span>
            </div>
            <div id="hotelControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner"> <!-- <div class="carousel-inner card-image-top"> -->
                    <?php $numPictures = 0 ?>
                    <?php
                        if (sizeOf($booking->getRoom()->getPictureArray()) > 0) {
                            foreach ($booking->getRoom()->getPictureArray() as $picture) {
                    ?>
                        <?php $numPictures++ ?>
                        <div class="carousel-item<?php if ($numPictures == 1) echo ' active' ?>">
                            <img src="./hotel-images/<?php echo $picture->getFilename() ?>" class="d-block w-100" />
                        </div>
                    <?php
                            }
                        } else {
                            echo "<span>Not Available</span>";
                        }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#hotelControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#hotelControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
             </div>

            <div class="card-body">
                <h5 class="card-title"><?php echo $booking->getRoom()->getLabel() ?></h5>
                <!-- <h6 class="card-title">From: <?php //echo $lowestPrice ?></h6> -->
                <p class="card-text"><?php echo $booking->getRoom()->getPrice() ?></p>
            </div>
        </div>


    </div>

</div>

<div class="container">
    <div class= "card bg-light mb-3">
        <div class="card-header">
            <h5>Booking Details</h5>
        </div>
        <div class="card-body">
            <span>From: <?php echo $booking->getStartDate() ?></span><br />
            <span>To: <?php echo $booking->getEndDate() ?></span><br />
            <span>Nights: <?php echo $booking->getNights() ?></span><br />
            <span>Price per Night: <?php echo $booking->getRoom()->getPrice() ?></span><br />
            <span>Total Price: <?php echo $booking->getPrice() ?></span><br />
        </div>
        <div class="card-footer">
            <form method="POST" name="confirmForm" action="confirmbooking.php">
                <button type="submit" name="confirm" class="btn btn-primary mt-3 mb-2">Confirm</button><a href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><button type="button" name="back" class="btn btn-primary mt-3 mb-2">Back</button></a>
            </form>
        </div>
    </div>
</div>
<?php require('./includes/footer.html'); ?>