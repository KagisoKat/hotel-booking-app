<?php require('./includes/header.html'); ?>
<div class="container">
    <div class= "card bg-light mb-3">
        <div class="card-header">
            Thank you!
        <div class="card-body">
            <p>Thank you for booking!</p>
            <a href="index.php"><button type="button" class="btn btn-primary mt-3 mb-2">Main Page</button></a>
            <?php if (isset($_GET['booking_id'])) { ?>
                <a href="downloadbooking.php?booking_id=<?php echo $_GET['booking_id']; ?>"><button type="button" class="btn btn-primary mt-3 mb-2">Download Receipt</button></a>
            <?php } ?>
        </div>
    </div>
</div>
<?php require('./includes/footer.html'); ?>