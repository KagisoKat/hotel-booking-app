<?php
require_once('./lib/autoloader.php');

if (isset($_POST['reset'])) {
    require('./config/db.php');


    $staff = new HotelClasses\Staff();
    $staff->setEmail(filter_var($_POST["staffEmail"], FILTER_SANITIZE_EMAIL));
    $staff->setPassword(filter_var($_POST["staffPassword"], FILTER_SANITIZE_STRING));
    $staff->hashPassword();

    if (filter_var($staff->getEmail(), FILTER_SANITIZE_EMAIL)) {
        $stmt = $pdo->prepare('SELECT * from staff WHERE staff_email = ? ');
        $stmt->execute([$staff->getEmail()]);
        $totalUsers = $stmt->rowCount();

        if ($totalUsers != 1) {
            $emailNotExist = "Email does not exist";
        } else {
            $stmt = $pdo->prepare('UPDATE staff SET staff_password = ? WHERE staff_email = ?');
            $stmt->execute([$staff->getPasswordHashed(), $staff->getEmail()]);
            header('Location: index.php');
        }
    }
}
?>
<!-- the register page for both members and librarians -->
<?php require('./includes/header.html'); ?>


<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Reset Password</div>
        <div class="card-body">
            <form action="forgot-cms.php" method="POST">
                <div class="form-group">
                    <label for="staffEmail">Email</label>
                    <input required type="email" name="staffEmail" class="form-control" />
                    <br />
                    <?php if (isset($emailNotExist)) { ?>
                        <p>
                            <?php echo $emailNotExist ?>
                        <p>
                        <?php }
                    $emailEmailNotExist ?>
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input required type="password" name="staffPassword" class="form-control" />
                </div>
                <br />
                <button name="reset" type="submit" class="btn btn-primary">Reset</button>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>