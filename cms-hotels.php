<!-- for librarians to login and view books and authors, etc -->

<?php

    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if ($userType!='cms')
        header("Location: index.php");

    if (!isset($_GET["sorting"])) {
        $hotelSorting = 'id';
    } else {
        $hotelSorting = $_GET['sorting'];
    }

    if (isset($_POST['search'])) {
        $searchString = "%" . filter_var($_POST["searchText"], FILTER_SANITIZE_STRING) . "%";
        $hotelQuery = 'SELECT * FROM hotels WHERE hotel_name LIKE :ss OR hotel_address LIKE :ss';
    } else {
        $hotelQuery = 'SELECT * FROM hotels';
    }

    if ($hotelSorting == 'id') {
        $hotelQuery .= ' ORDER BY hotels.hotel_id';
    } elseif ($hotelSorting == 'hname') {
        $hotelQuery .= ' ORDER BY hotels.hotel_name';
    } elseif ($hotelSorting == 'haddr') {
        $hotelQuery .= ' ORDER BY hotels.hotel_address';
    } elseif ($hotelSorting == 'hrating') {
        $hotelQuery .= ' ORDER BY hotels.hotel_rating';
    }

    if (isset($_POST['search'])) {
        $stmt = $pdo->prepare($hotelQuery);
        $stmt->bindValue(':ss', $searchString);
    } else {
        $stmt = $pdo->prepare($hotelQuery);
    }
    $stmt->execute();

    $allHotels = $stmt->fetchAll();
?>
<?php require('./includes/header.html'); ?>
<div class="container">
    <div class="content">
        <form method="post" name="searchForm" action="cms-hotels.php?sorting=<?php echo $hotelSorting; ?>">
            <input type="text" name="searchText" class="form-control mt-2" />
            <button name="search" type="submit" class="btn btn-primary mt-3 mb-2">Search</button>
        </form>
    </div>

    <div>
        <p>Sorting:
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=id">ID</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=hname">Hotel Name</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=haddr">Hotel Address</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=hrating">Hotel Rating</a>
        </p>
    </div>
<div class="container">
    <div class="content">
        <a href="cms-registerhotel.php"><button name="register" type="button" class="btn btn-primary mt-3 mb-2">Register Hotel</button></a>
    </div>
<div>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Hotel Name</th>
        <th>Hotel Address</th>
        <th>Hotel Rating</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>


<?php
// output data of each row
foreach ($allHotels as $hotel_item) {
    $oneHotel = new HotelClasses\Hotel();
    $oneHotel->setId($hotel_item->hotel_id);
    $oneHotel->setName($hotel_item->hotel_name);
    $oneHotel->setAddress($hotel_item->hotel_address);
    $oneHotel->setRating($hotel_item->hotel_rating);
    echo "<tr>";
    echo "<td>" . $oneHotel->getId() . "</td>";
    echo "<td>" . $oneHotel->getName() . "</td>";
    echo "<td>" . $oneHotel->getAddress() . "</td>";
    echo "<td>" . $oneHotel->getRating() . "</td>";
    echo '<td><a href="cms-edithotel.php?hotel_id=' . $oneHotel->getId() . '">Edit</a></td>';
    echo '<td><a href="cms-deletehotel.php?hotel_id=' . $oneHotel->getId() . '">Delete</a></td>';
    echo "</tr>";
}
?>
</table>
</div>

<?php require('./includes/footer.html'); ?>