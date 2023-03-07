<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    require('./lib/gen-uuid.php');

    if ($userType!='cms')
        header("Location: index.php");

    if ((isset($_GET['room_id'])) && (isset($_GET['hotel_id']))) {
        $editRoom = new HotelClasses\Room();
        $editRoom->setId($_GET['room_id']);

        $stmt = $pdo->prepare('SELECT * FROM rooms INNER JOIN hotels ON rooms.hotel_id = hotels.hotel_id WHERE rooms.room_id=?');
        $stmt->execute([$editRoom->getId()]);

        $room_item=$stmt->fetch();

        $editRoom->setLabel($room_item->room_label);
        $editRoom->setPrice($room_item->room_price);
        $editRoom->setHotelId($room_item->hotel_id);
        $editRoom->setHotelName($room_item->hotel_name);

        $stmt = $pdo->prepare('SELECT * FROM room_pictures WHERE room_id=?');
        $stmt->execute([$editRoom->getId()]);

        $picture_items = $stmt->fetchAll();

        foreach($picture_items as $picture) {
            $newPicture = new HotelClasses\RoomPicture;
            $newPicture->setId($picture->rp_id);
            $newPicture->setFilename($picture->rp_filename);

            $editRoom->addPicture($newPicture);
        }

    }
    
    if (isset($_POST['update'])) {
        $room = new HotelClasses\Room();
        $room->setId($_GET['room_id']);
        $room->setLabel(filter_var($_POST["roomName"], FILTER_SANITIZE_STRING));
        $room->setPrice(filter_var($_POST["roomPrice"], FILTER_SANITIZE_STRING));
        
        $stmt = $pdo->prepare('UPDATE rooms SET room_label = ?, room_price = ? WHERE room_id = ?');
        $stmt->execute([$room->getLabel(), $room->getPrice(), $room->getId()]);
        header('Location: cms-editroom.php?room_id=' . $room.getid() . '&hotel_id=' . $room->getHotelId());
    }

    if (isset($_FILES['roomPictureUpload'])) {
        $errors= array();
        $file_size = $_FILES['roomPictureUpload']['size'];
        $file_tmp = $_FILES['roomPictureUpload']['tmp_name'];
        $file_type = $_FILES['roomPictureUpload']['type'];
        $file_ext = strtolower(end(explode('.',$_FILES['roomPictureUpload']['name'])));
        $file_name = genuuid() . "." . $file_ext;
      
        $extensions= array("jpeg","jpg","png");
      
        if(in_array($file_ext,$extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
      
        if($file_size > 2097152){
            $errors[]='File size must be max. 2 MB';
        }
      
        if(empty($errors)==true){
            echo "Moving " . $file_tmp . " to hotel-images/" . $file_name . " for room_id " . $editRoom->getId();
            $stmt = $pdo->prepare('INSERT INTO room_pictures(rp_filename, room_id) VALUES (?, ?)');
            $stmt->execute([$file_name, $editRoom->getId()]);
            move_uploaded_file($file_tmp,"hotel-images/".$file_name);
            echo "Success";
            header('Location: cms-editroom.php?room_id=' . $editRoom->getId() . '&hotel_id=' . $editRoom->getHotelId());
        } else {
            print_r($errors);
        }
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Update Room</div>
        <div class="card-body">
            <form action="cms-editroom.php?room_id=<?php echo $editRoom->getId() ?>&hotel_id=<?php echo $editRoom->getHotelId() ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="roomName">Room Name/Number</label>
                    <input required type="text" name="roomName" class="form-control" value="<?php echo $editRoom->getLabel() ?>" />
                </div>
                <div class="form-group">
                    <label for="roomPrice">Price</label>
                    <input required type="text" name="roomPrice" class="form-control" value="<?php echo $editRoom->getPrice() ?>" />
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
                            foreach ($editRoom->getPictureArray() as $picture) {
                                echo '<tr>';
                                echo '<td>' . $picture->getId() . '</td>';
                                echo '<td>' . $picture->getFilename() . '</td>';
                                echo '<td><img style="object-fit:contain;width:100%;height:20em;" src="./hotel-images/' . $picture->getFilename() .'" /></td>';
                                echo '<td><a href="cms-deleteroompicture.php?picture_id=' . $picture->getId() . '&room_id='. $editRoom->getId() .'&hotel_id=' . $editRoom->getHotelId . '">Delete</a></td>';
                                echo '</tr>';
                            }
                        ?>
                    </table>
                    <label for="roomPic">Upload Picture</label>
                    <input required type="file" name="roomPictureUpload" class="form-control" />
                    <br />
                    <button name="uploadpic" type="submit" class="btn btn-primary">Upload Picture</button>
                </div>
                <br />
                <br />
                <div class="form-group">
                    <button name="update" type="submit" class="btn btn-primary">Update Room</button>
                    <a href="cms-edithotel.php?hotel_id=<?php echo $editRoom->getHotelId() ?>"><button name="backToRooms" type="button" class="btn btn-primary">Back</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>