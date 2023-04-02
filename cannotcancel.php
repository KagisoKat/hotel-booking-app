<!-- for librarians to login and view books and authors, etc -->

<?php

    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
?>
<?php require('./includes/header.html'); ?>
<div class="container">
    <div class="content">
    </div>
</div>


<div class="container">
    <div class= "card bg-light mb-3">
        <div class="card-header">
            <h5><strong>Cannot cancel booking</strong></h5>
        </div>
        <div class="card-body">
            <h4>Booking cannot be cancelled less than 48 hours before commencement</h4>
            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button type="button" class="btn btn-primary">Back</button></a>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>