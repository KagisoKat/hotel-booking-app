<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');

    if ($userType=='guest')
        header("Location: index.php");
    
    if (isset($_POST['update'])) {
        // First check if email is taken
        if ($userType=='user') {
            $updatedUser = new HotelClasses\User();
            $updatedUser->setId($_SESSION['userId']);
            $updatedUser->setTitle(filter_var($_POST["userTitle"], FILTER_SANITIZE_STRING));
            $updatedUser->setFirstName(filter_var($_POST["userFirstName"], FILTER_SANITIZE_STRING));
            $updatedUser->setLastName(filter_var($_POST["userLastName"], FILTER_SANITIZE_STRING));
            $updatedUser->setEmail(filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL));
            $updatedUser->setAddress(filter_var($_POST["userAddress"], FILTER_SANITIZE_STRING));
            //$updatedUser->setPassword(filter_var($_POST["userPassword"], FILTER_SANITIZE_STRING));
            //$updatedUser->hashPassword();
        
            $stmt = $pdo->prepare('UPDATE users SET user_title = ?, user_firstname = ?, user_lastname = ?, user_email = ?, user_address = ? WHERE user_id = ?');
            $stmt->execute([$updatedUser->getTitle(), $updatedUser->getFirstName(), $updatedUser->getLastName(), $updatedUser->getEmail(), $updatedUser->getAddress(), $updatedUser->getId()]);
        } elseif ($userType=='cms') {
            $updatedStaff = new HotelClasses\User();
            $updatedStaff->setId($_SESSION['userId']);
            $updatedStaff->setFirstName(filter_var($_POST["userFirstName"], FILTER_SANITIZE_STRING));
            $updatedStaff->setLastName(filter_var($_POST["userLastName"], FILTER_SANITIZE_STRING));
            $updatedStaff->setEmail(filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL));
            //$updatedStaff->setPassword(filter_var($_POST["userPassword"], FILTER_SANITIZE_STRING));
            //$updatedStaff->hashPassword();
        
            $stmt = $pdo->prepare('UPDATE staff SET staff_firstname = ?, staff_lastname = ?, staff_email = ? WHERE staff_id = ?');
            $stmt->execute([$updatedStaff->getFirstName(), $updatedStaff->getLastName(), $updatedStaff->getEmail(), $updatedStaff->getId()]);
        }
        header('Location: index.php');
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Update Your Details</div>
        <div class="card-body">
            <form action="profile.php" method="POST">
                <div class="form-group">
                    <label for="userID"><?php if ($userType == "user") echo "User ID"; elseif ($userType == "cms") echo "CMS ID"; ?></label>
                    <input required type="text" name="userID" class="form-control" value="<?php if ($userType == "user") echo $user->getID(); elseif ($userType == "cms") echo $staff->getID(); ?>" readonly/>
                </div>
                <br />
                <?php if ($userType=="user") { ?>
                    <div class="form-group">
                        <select name="userTitle">
                            <?php
                                $titles=array("Mr.", "Mrs.", "Miss", "Miss.", "Ms.", "Dr.", "Prof.");

                                foreach ($titles as $title) {
                                    echo '<option value="' . $title . '"';
                                    if ($user->getTitle() == $title) echo ' selected="selected"';
                                    echo '">' . $title . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label for="userFirstName">First Name</label>
                    <input required type="text" name="userFirstName" class="form-control" value="<?php if ($userType == "user") echo $user->getFirstName(); elseif ($userType == "cms") echo $staff->getFirstName(); ?>" />
                </div>
                <div class="form-group">
                    <label for="userLastName">Last Name</label>
                    <input required type="text" name="userLastName" class="form-control" value="<?php if ($userType == "user") echo $user->getLastName(); elseif ($userType == "cms") echo $staff->getLastName(); ?>" />
                </div>
                <div class="form-group">
                    <label for="userEmail">User Email</label>
                    <input required type="email" name="userEmail" class="form-control" value="<?php if ($userType == "user") echo $user->getEmail(); elseif ($userType == "cms") echo $staff->getEmail(); ?>" />
                    <?php if (isset($emailTaken)) { ?>
                        <p><?php echo $emailTaken ?>
                        <p>
                        <?php }
                    $emailTaken ?>
                </div>
                <?php if ($userType=="user") { ?>
                <div class="form-group">
                    <label for="userAddress">Address</label>
                    <input required type="text" name="userAddress" class="form-control" value="<?php echo $user->getAddress(); ?>" />
                </div> <?php } ?>
                <br />
                <div class="form-group">
                    <button name="update" type="submit" class="btn btn-primary">Update the details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>