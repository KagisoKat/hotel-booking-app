<?php
    session_start();
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    if (isset($_SESSION['userType'])) $userType=$_SESSION['userType'];
    else $_SESSION['userType']='guest';
    require('./includes/header.html');
    require_once('./config/db.php');

    if (isset($_POST['search'])) {
        $searchString = "%" . filter_var($_POST["searchText"], FILTER_SANITIZE_STRING) . "%";
        $stmt = $pdo->prepare('SELECT * FROM hotels WHERE hotels.hotel_name LIKE :ss');
        $stmt->bindValue(':ss', $searchString);
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM hotels');
        $stmt->execute();
    }

    $allHotels = $stmt->fetchAll();
?>

<div class="content" >
    <form method="post" name="searchForm" action="index.php">
        <input type="text" name="searchText" class="form-control mt-2" />
        <button name="search" type="submit" class="btn btn-dark mt-3 mb-2">Search</button>
    </form>
</div>
<div class="row row-cols-md-4">
    <?php 
        foreach ($allHotels as $hotel) { 
            $hotelCard=new HotelClasses\Hotel();
            $hotelCard->setId($hotel->hotel_id);
            $hotelCard->setName($hotel->hotel_name);
            $hotelCard->setAddress($hotel->hotel_address);
            $hotelCard->setRating((int)$hotel->hotel_rating);
            $hotelCardStmt = $pdo->prepare('SELECT * FROM rooms WHERE hotel_id=?');
            $hotelCardStmt->execute([$hotelCard->getId()]);
            $hotelCardRoomsResult = $hotelCardStmt->fetchAll();

            $numRooms=0;
            $lowestPrice=9999999.0;
            foreach ($hotelCardRoomsResult as $room_item) {
                $numRooms++;
                $hotelCardRoom = new HotelClasses\Room();
                $hotelCardRoom->setId($room_item->room_id);
                $hotelCardRoom->setLabel($room_item->room_label);
                $hotelCardRoom->setPrice($room_item->room_price);
                if ($lowestPrice > $hotelCardRoom->getPrice()) $lowestPrice = $hotelCardRoom->getPrice();
                $hotelCard->addRoom($hotelCardRoom);
            }

            $hotelCardPictureStmt = $pdo->prepare('SELECT * FROM hotel_pictures WHERE hotel_id=?');
            $hotelCardPictureStmt->execute([$hotelCard->getId()]);
            $hotelCardPicture = $hotelCardPictureStmt->fetchAll();

            foreach ($hotelCardPicture as $picture_item) {
                $hotelCardPicture = new HotelClasses\HotelPicture;
                $hotelCardPicture->setId($picture_item->hp_id);
                $hotelCardPicture->setFilename($picture_item->hp_filename);
                $hotelCard->addPicture($hotelCardPicture);
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
                            if (sizeof($hotelCard->getPictureArray())) {
                                foreach ($hotelCard->getPictureArray() as $picture) {
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
                    <h5 class="card-title"><?php echo $hotelCard->getName() ?></h5>
                    <h6>
                    <?php
                        for ($starCount = 0; $starCount < $hotelCard->getRating(); $starCount++) {
                            //echo "\u{2730}";
                            echo "\u{272D}";
                        } 
                    ?>
                    </h6>
                    <h6 class="card-title">From: <?php echo $lowestPrice ?></h6>
                    <p class="card-text"><?php echo $hotelCard->getAddress() ?></p>
                </div>
                <div class="card-footer">
                    <a href="viewhotel.php?hotel_id=<?php echo $hotelCard->getId() ?>"><button type="button" class="btn btn-dark mt-3 mb-2">View Hotel</button></a>
                    <?php if ($_SESSION['userType'] == 'cms') { ?>
                        <a href="createbooking.php?hotel_id=<?php echo $hotelCard->getId() ?>"><button type="button" class="btn btn-dark mt-3 mb-2">Book</button></a>
                    <?php } elseif ($_SESSION['userType'] == 'user') { ?>
                        <a href="createbooking.php?hotel_id=<?php echo $hotelCard->getId() ?>"><button type="button" class="btn btn-dark mt-3 mb-2">Book</button></a>
                    <?php } else { ?>
                        <a href="register.php"><button type="button" class="btn btn-dark mt-3 mb-2">Register or log in to Book</button></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

</div>

<?php require('./includes/footer.html'); ?>