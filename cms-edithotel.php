<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    require('./lib/gen-uuid.php');

    if ($userType!='cms')
        header("Location: index.php");

    if (isset($_GET['hotel_id'])) {
        $editHotel = new HotelClasses\Hotel();
        $editHotel->setId($_GET['hotel_id']);

        $stmt = $pdo->prepare('SELECT * FROM hotels WHERE hotel_id=?');
        $stmt->execute([$editHotel->getId()]);

        $hotel_item=$stmt->fetch();

        $editHotel->setName($hotel_item->hotel_name);
        $editHotel->setAddress($hotel_item->hotel_address);
        $editHotel->setRating($hotel_item->hotel_rating);

        $stmt = $pdo->prepare('SELECT * FROM hotel_pictures WHERE hotel_id=?');
        $stmt->execute([$editHotel->getId()]);

        $picture_items=$stmt->fetchAll();

        foreach($picture_items as $picture) {
            $newPicture = new HotelClasses\HotelPicture;
            $newPicture->setId($picture->hp_id);
            $newPicture->setFilename($picture->hp_filename);

            $editHotel->addPicture($newPicture);
        }
     }
    
    if (isset($_POST['update'])) {
        $hotel = new HotelClasses\Hotel();
        $hotel->setId($_GET['hotel_id']);
        $hotel->setName(filter_var($_POST["hotelName"], FILTER_SANITIZE_STRING));
        $hotel->setAddress(filter_var($_POST["hotelAddress"], FILTER_SANITIZE_STRING));
        $hotel->setRating(filter_var($_POST["hotelRating"], FILTER_SANITIZE_NUMBER_INT));
        
        $stmt = $pdo->prepare('UPDATE hotels SET hotel_name = ?, hotel_address = ?, hotel_rating = ? WHERE hotel_id = ?');
        $stmt->execute([$hotel->getName(), $hotel->getAddress(), $hotel->getRating(), $hotel->getId()]);
        header('Location: cms-edithotel.php?hotel_id=' . $hotel->getId());
    }

    if (isset($_FILES['hotelPictureUpload'])) {
        $errors= array();
        $file_size = $_FILES['hotelPictureUpload']['size'];
        $file_tmp = $_FILES['hotelPictureUpload']['tmp_name'];
        $file_type = $_FILES['hotelPictureUpload']['type'];
        $file_ext = strtolower(end(explode('.',$_FILES['hotelPictureUpload']['name'])));
        $file_name = genuuid() . "." . $file_ext;
      
        $extensions= array("jpeg","jpg","png");
      
        if(in_array($file_ext,$extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
      
        if($file_size > 2097152){
            $errors[]='File size must be max. 2 MB';
        }
      
        if(empty($errors)==true){
            echo "Moving " . $file_tmp . " to hotel-images/" . $file_name . " for hotel_id " . $editHotel->getId();
            $stmt = $pdo->prepare('INSERT INTO hotel_pictures(hp_filename, hotel_id) VALUES (?, ?)');
            $stmt->execute([$file_name, $editHotel->getId()]);
            move_uploaded_file($file_tmp,"hotel-images/".$file_name);
            echo "Success";
            header('Location: cms-edithotel.php?hotel_id=' . $editHotel->getId());
        } else {
            print_r($errors);
        }
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Update Hotel</div>
        <div class="card-body">
            <form action="cms-edithotel.php?hotel_id=<?php echo $editHotel->getId() ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="hotelName">Hotel Name</label>
                    <input required type="text" name="hotelName" class="form-control" value="<?php echo $editHotel->getName() ?>" />
                </div>
                <div class="form-group">
                    <label for="hotelAddress">Hotel Address</label>
                    <input required type="text" name="hotelAddress" class="form-control" value="<?php echo $editHotel->getAddress() ?>" />
                </div>
                <div class="form-group">
                    <label for="hotelRating">Hotel Rating</label>
                    <input required type="text" name="hotelRating" class="form-control" value="<?php echo $editHotel->getRating() ?>" />
                </div>
                <br />
                <div class="form-group">
                    <h3>Pictures</h3>
                    <table border=1 width="100%">
                        <tr>
                            <th>Id</th>
                            <th>Filename</th>
                            <th>Picture</th>
                            <th>Delete</th>
                        </tr>
                        <?php
                            foreach ($editHotel->getPictureArray() as $picture) {
                                echo '<tr>';
                                echo '<td>' . $picture->getId() . '</td>';
                                echo '<td>' . $picture->getFilename() . '</td>';
                                echo '<td><img style="object-fit:contain;width:100%;height:20em;" src="./hotel-images/' . $picture->getFilename() .'" /></td>';
                                echo '<td><a href="cms-deletehotelpicture.php?picture_id=' . $picture->getId() . '&hotel_id='. $editHotel->getId() .'">Delete</a></td>';
                                echo '</tr>';
                            }
                        ?>
                    </table>
                    <label for="hotelPic">Upload Picture</label>
                    <input required type="file" name="hotelPictureUpload" class="form-control" value="<?php echo $editHotel->getRating() ?>" />
                    <br />
                    <button name="uploadpic" type="submit" class="btn btn-primary">Upload Picture</button>
                </div>
                <br />
                <div class="form-group">
                    <button name="update" type="submit" class="btn btn-primary">Update Hotel</button>
                    <a href="cms-hotels.php"><button name="backToHotels" type="button" class="btn btn-primary">Back</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>