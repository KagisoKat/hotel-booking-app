<?php
    session_start();
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');

    if (isset($_SESSION['userType'])) $userType=$_SESSION['userType'];
    else $userType='guest';
    
    if (!isset($_GET['hotel_id']))
        header("Location: index.html");
    $hotelId=$_GET['hotel_id'];

    require('./includes/header.html');
    require_once('./config/db.php');

    if (isset($_POST['search'])) {
        $searchString = "%" . filter_var($_POST["searchText"], FILTER_SANITIZE_STRING) . "%";
        $stmt = $pdo->prepare('SELECT * FROM rooms WHERE rooms.hotel_id = :hid AND rooms.room_label LIKE :ss');
        $stmt->bindValue(':hid', $hotelId);
        $stmt->bindValue(':ss', $searchString);
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM rooms WHERE rooms.hotel_id LIKE :hid');
        $stmt->bindValue(':hid', $hotelId);
        $stmt->execute();
    }
    $allRooms = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT * FROM hotels WHERE hotels.hotel_id = :hid');
    $stmt->bindValue(':hid', $hotelId);
    $stmt->execute();
    $hotelRecord = $stmt->fetch();

    $thisHotel = new HotelClasses\Hotel;
    $thisHotel->setId($hotelRecord->hotel_id);
    $thisHotel->setName($hotelRecord->hotel_name);
    $thisHotel->setAddress($hotelRecord->hotel_address);
    $thisHotel->setRating($hotelRecord->hotel_rating);

    $stmt = $pdo->prepare('SELECT * FROM hotel_pictures WHERE hotel_pictures.hotel_id = :hid');
    $stmt->bindValue(':hid', $hotelId);
    $stmt->execute();

    $allPictures = $stmt->fetchAll();

    foreach ($allPictures as $picture) {
        $thisPicture = new HotelClasses\HotelPicture;
        $thisPicture->setId($picture->hp_id);
        $thisPicture->setFilename($picture->hp_filename);
        $thisHotel->addPicture($thisPicture);
    }
 
?>

<div class="content">
    <form method="post" name="searchForm" action="viewhotel.php?hotel_id=<?php echo $hotelId ?>">
        <input type="text" name="searchText" class="form-control mt-2" />
        <button name="search" type="submit" class="btn btn-primary mt-3 mb-2">Search</button>
    </form>
</div>

<div class="container">
    <div class= "card bg-light mb-3">
        <div class="card-header">
            <h5><?php echo $thisHotel->getName() ?></h5>
        </div>
        <div class="card-body">
            <div class="carousel-inner"> <!-- <div class="carousel-inner card-image-top"> -->
                <?php $numPictures = 0 ?>
                <?php
                    if (sizeof($thisHotel->getPictureArray())) {
                        foreach ($thisHotel->getPictureArray() as $picture) {
                ?>
                    <?php $numPictures++ ?>
                    <div class="carousel-item<?php if ($numPictures == 1) echo ' active' ?>">
                        <img src="./hotel-images/<?php echo $picture->getFilename() ?>" class="d-block w-100" />
                    </div>
                <?php
                        }
                    } else {
                        echo "<span>Picture Not Available</span>";
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
        <div class="card-footer">
            <h6>
                <?php
                    for ($starCount = 0; $starCount < $thisHotel->getRating(); $starCount++) {
                        //echo "\u{2730}";
                        echo "\u{272D}";
                    } 
                ?>
            </h6>
            <p><?php echo $thisHotel->getAddress() ?></p>
        </div>
    </div>
</div>


<div class="row row-cols-md-4">
    <?php 
        foreach ($allRooms as $room) { 
            $roomCard=new HotelClasses\Room();
            $roomCard->setId($room->room_id);
            $roomCard->setLabel($room->room_label);
            $roomCard->setPrice($room->room_price);
            $thisHotel->addRoom($roomCard);

            $roomCardPictureStmt = $pdo->prepare('SELECT * FROM room_pictures WHERE room_id=?');
            $roomCardPictureStmt->execute([$roomCard->getId()]);
            $roomCardPicture = $roomCardPictureStmt->fetchAll();

            foreach ($roomCardPicture as $picture_item) {
                $roomCardPicture = new HotelClasses\RoomPicture;
                $roomCardPicture->setId($picture_item->rp_id);
                $roomCardPicture->setFilename($picture_item->rp_filename);
                $roomCard->addPicture($roomCardPicture);
            }

            //echo "<pre>";
            //var_dump($hotelCard);
            //echo "</pre>";
    ?>
        <div class="col">
            <div class="card mx-2 my-2">
                
                <div id="hotelControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner"> <!-- <div class="carousel-inner card-image-top"> -->
                        <?php $numPictures = 0 ?>
                        <?php
                            if (sizeOf($roomCard->getPictureArray()) > 0 ) {
                                foreach ($roomCard->getPictureArray() as $picture) {
                        ?>
                            <?php $numPictures++ ?>
                            <div class="carousel-item<?php if ($numPictures == 1) echo ' active' ?>">
                                <img src="./hotel-images/<?php echo $picture->getFilename() ?>" class="d-block w-100" />
                            </div>
                        <?php
                                }
                            } else {
                                echo "<span>Picture Not Available</span>";
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
                    <h5 class="card-title"><?php echo $roomCard->getLabel() ?></h5>
                    <p class="card-text"><?php echo $roomCard->getPrice() ?></p>
                </div>
                <div class="card-footer">
                    <a href="viewhotel.php?hotel_id=<?php echo $thisHotel->getId() ?>&room_id=<?php echo $roomCard->getId() ?>"><button type="button" class="btn btn-primary mt-3 mb-2">View Room</button></a>
                    <?php if ($_SESSION['userType'] == 'cms') { ?>
                        <a href="createbooking.php?hotel_id=<?php echo $thisHotel->getId() ?>&room_id=<?php echo $roomCard->getId() ?>"><button type="button" class="btn btn-primary mt-3 mb-2">Book</button></a>
                    <?php } elseif ($_SESSION['userType'] == 'user') { ?>
                        <a href="createbooking.php?hotel_id=<?php echo $thisHotel->getId() ?>&room_id=<?php echo $roomCard->getId() ?>"><button type="button" class="btn btn-primary mt-3 mb-2">Book</button></a>
                    <?php } else { ?>
                        <a href="register.php"><button type="button">Register or log in to Book</button></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

</div>
<?php require('./includes/footer.html'); ?>