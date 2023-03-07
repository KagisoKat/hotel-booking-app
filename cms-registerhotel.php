<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');

    if ($userType!='cms')
        header("Location: index.php");
    
    if (isset($_POST['register'])) {
        $hotel = new HotelClasses\Hotel();
        $hotel->setName(filter_var($_POST["hotelName"], FILTER_SANITIZE_STRING));
        $hotel->setAddress(filter_var($_POST["hotelAddress"], FILTER_SANITIZE_STRING));
        $hotel->setRating(filter_var($_POST["hotelRating"], FILTER_SANITIZE_NUMBER_INT));
        
        $stmt = $pdo->prepare('INSERT INTO hotels (hotel_name, hotel_address, hotel_rating) VALUES (?, ?, ?)');
        $stmt->execute([$hotel->getName(), $hotel->getAddress(), $hotel->getRating()]);
        header('Location: cms-hotels.php');
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Register Hotel</div>
        <div class="card-body">
            <form action="cms-registerhotel.php" method="POST">
                <div class="form-group">
                    <label for="hotelName">Hotel Name</label>
                    <input required type="text" name="hotelName" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="hotelAddress">Hotel Address</label>
                    <input required type="text" name="hotelAddress" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="hotelRating">Hotel Rating</label>
                    <input required type="text" name="hotelRating" class="form-control" />
                </div>
                <br />
                <div class="form-group">
                    <button name="register" type="submit" class="btn btn-primary">Register Hotel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>