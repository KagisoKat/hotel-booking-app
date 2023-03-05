<?php
    session_start();
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./includes/header.html');
    if (isset($_SESSION['userType'])) $userType=$_SESSION['userType'];
    else $userType='guest';
?>

<div class="container">
    <div class= "card bg-light mb-3">
        <div class="card-header">
            <?php  if ($userType == 'user') { ?>
                <h5>Welcome <?php echo $user->getTitle() . " " . $user->getFirstName() . " " . $user->getLastName() . "!" ?></h5>
            <?php  } elseif ($userType == 'cms') { ?>
                <h5>Welcome <?php echo "CMS " . $staff->getFirstName() . " " . $staff->getLastName() . "!" ?></h5>
            <?php } else { ?>
                <h5>Welcome Guest!</h5>
            <?php } ?>
        </div>
        <div class="card-body">
            <h1>Show hotels here regardless of login status</h1>
            <?php  if ($userType == "user") { ?>
                <h4>Currently logged in as a user.</h4>
            <?php  } elseif ($userType == "cms") { ?>
                <h4>Currently logged in as CMS.</h4>
            <?php } else { ?>
                <h4>Currently logged in as a guest.</h4>
            <?php } ?>
        </div>
    </div>
</div>
<?php require('./includes/footer.html'); ?>