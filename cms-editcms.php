<?php
    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');

    if ($userType!='cms')
        header("Location: index.php");
    
    if (isset($_GET['cms_id'])) {
        $editStaff = new HotelClasses\Staff();
        $editStaff->setId($_GET['cms_id']);

        $stmt = $pdo->prepare('SELECT * FROM staff WHERE staff_id=?');
        $stmt->execute([$editStaff->getId()]);

        $staff_item=$stmt->fetch();

        $editStaff->setFirstName($staff_item->staff_firstname);
        $editStaff->setLastName($staff_item->staff_lastname);
        $editStaff->setEmail($staff_item->staff_email);
     }
    
    if (isset($_POST['update'])) {
        $updatedStaff = new HotelClasses\Staff();
        $updatedStaff->setId($_GET['cms_id']);
        $updatedStaff->setFirstName(filter_var($_POST["staffFirstName"], FILTER_SANITIZE_STRING));
        $updatedStaff->setLastName(filter_var($_POST["staffLastName"], FILTER_SANITIZE_STRING));
        $updatedStaff->setEmail(filter_var($_POST["staffEmail"], FILTER_SANITIZE_EMAIL));
        //$updatedStaff->setPassword(filter_var($_POST["userPassword"], FILTER_SANITIZE_STRING));
        //$updatedStaff->hashPassword();
        
        $stmt = $pdo->prepare('UPDATE staff SET staff_firstname = ?, staff_lastname = ?, staff_email = ? WHERE staff_id = ?');
        $stmt->execute([$updatedStaff->getFirstName(), $updatedStaff->getLastName(), $updatedStaff->getEmail(), $updatedStaff->getId()]);
        header('Location: cms-admin.php');
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Update Your Details</div>
        <div class="card-body">
            <form action="cms-editcms.php?cms_id=<?php echo $_GET['cms_id'] ?>" method="POST">
                <div class="form-group">
                </div>
                <div class="form-group">
                    <label for="staffFirstName">First Name</label>
                    <input required type="text" name="staffFirstName" class="form-control" value="<?php echo $editStaff->getFirstName() ?>" />
                </div>
                <div class="form-group">
                    <label for="staffLastName">Last Name</label>
                    <input required type="text" name="staffLastName" class="form-control" value="<?php echo $editStaff->getLastName() ?>" />
                </div>
                <div class="form-group">
                    <label for="staffEmail">User Email</label>
                    <input required type="email" name="staffEmail" class="form-control" value="<?php echo $editStaff->getEmail() ?>" />
                    <?php if (isset($emailTaken)) { ?>
                        <p><?php echo $emailTaken; ?></p> <?php } ?>
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