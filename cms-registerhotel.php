<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');

    if ($userType!='cms')
        header("Location: index.php");
    
    if (isset($_GET['user_id'])) {
        $editUser = new HotelClasses\User();
        $editUser->setId($_GET['user_id']);

        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id=?');
        $stmt->execute([$editUser->getId()]);

        $user_item=$stmt->fetch();

        $editUser->setTitle($user_item->user_title);
        $editUser->setFirstName($user_item->user_firstname);
        $editUser->setLastName($user_item->user_lastname);
        $editUser->setEmail($user_item->user_email);
        $editUser->setAddress($user_item->user_address);
     }
    
    if (isset($_POST['update'])) {
        $updatedUser = new HotelClasses\User();
        $updatedUser->setId($_GET['user_id']);
        $updatedUser->setTitle(filter_var($_POST["userTitle"], FILTER_SANITIZE_STRING));
        $updatedUser->setFirstName(filter_var($_POST["userFirstName"], FILTER_SANITIZE_STRING));
        $updatedUser->setLastName(filter_var($_POST["userLastName"], FILTER_SANITIZE_STRING));
        $updatedUser->setEmail(filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL));
        $updatedUser->setAddress(filter_var($_POST["userAddress"], FILTER_SANITIZE_STRING));
        //$updatedUser->setPassword(filter_var($_POST["userPassword"], FILTER_SANITIZE_STRING));
        //$updatedUser->hashPassword();
        
        $stmt = $pdo->prepare('UPDATE users SET user_title = ?, user_firstname = ?, user_lastname = ?, user_email = ?, user_address = ? WHERE user_id = ?');
        $stmt->execute([$updatedUser->getTitle(), $updatedUser->getFirstName(), $updatedUser->getLastName(), $updatedUser->getEmail(), $updatedUser->getAddress(), $updatedUser->getId()]);
        header('Location: cms-users.php');
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Update Your Details</div>
        <div class="card-body">
            <form action="cms-edituser.php?user_id=<?php echo $_GET['user_id'] ?>" method="POST">
                <div class="form-group">
                    <select name="userTitle">
                        <?php
                            $titles=array("Mr.", "Mrs.", "Miss", "Miss.", "Ms.", "Dr.", "Prof.");

                            foreach ($titles as $title) {
                                echo '<option value="' . $title . '"';
                                if ($editUser->getTitle() == $title) echo ' selected="selected"';
                                echo '">' . $title . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="userFirstName">First Name</label>
                    <input required type="text" name="userFirstName" class="form-control" value="<?php echo $editUser->getFirstName() ?>" />
                </div>
                <div class="form-group">
                    <label for="userLastName">Last Name</label>
                    <input required type="text" name="userLastName" class="form-control" value="<?php echo $editUser->getLastName() ?>" />
                </div>
                <div class="form-group">
                    <label for="userEmail">User Email</label>
                    <input required type="email" name="userEmail" class="form-control" value="<?php echo $editUser->getEmail() ?>" />
                    <?php if (isset($emailTaken)) { ?>
                        <p><?php echo $emailTaken; ?></p> <?php } ?>
                </div>
                <div class="form-group">
                    <label for="userAddress">Address</label>
                    <input required type="text" name="userAddress" class="form-control" value="<?php echo $editUser->getAddress() ?>" />
                </div>
                <div class="form-group">
                    <label for="userPasword">Address</label>
                    <input required type="password" name="userPassword" class="form-control" value="" />
                </div>
                <br />
                <div class="form-group">
                    <button name="update" type="submit" class="btn btn-primary">Update the details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>