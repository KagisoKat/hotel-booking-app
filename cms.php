<!-- login page for both members and librarians -->

<?php
session_start();
require_once('./lib/autoloader.php');

if (isset($_POST['login'])) {
    require('./config/db.php');

    $staffEmail = filter_var($_POST["staffEmail"], FILTER_SANITIZE_EMAIL);
    $staffPassword = filter_var($_POST["staffPassword"], FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare('SELECT * FROM staff WHERE staff_email = ?');
    $stmt->execute([$staffEmail]);
    $staff_item = $stmt->FETCH();
    $staff = new HotelClasses\Staff();
    if (isset($staff_item)) {
        $staff->setId($staff_item->staff_id);
        $staff->setFirstName($staff_item->staff_firstname);
        $staff->setLastName($staff_item->staff_lastname);
        $staff->setEmail($staff_item->staff_email);
        $staff->setPasswordHashed($staff_item->staff_password);

        if (password_verify($staffPassword, $staff->getPasswordHashed())) {
            echo "Password correct";
            $_SESSION['userId'] = $staff->getId();
            $_SESSION['userType'] = "cms";
            header('Location: index.php');
        } else {
            $loginWrong = "Email or password is incorrect";
        }
    }

}
require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">CMS Login</div>
        <div class="card-body">
            <form action="cms.php" method="POST">
                <div class="form-group">
                    <label for="staffEmail">Email</label>
                    <input required type="email" name="staffEmail" class="form-control" />
                    <br />
                    <?php if (isset($loginWrong)) { ?>
                        <p>
                            <?php echo $loginWrong ?>
                        <p>
                        <?php } ?>
                </div>
                <div class="form-group">
                    <label for="staffPassword">Password</label>
                    <input required type="password" name="staffPassword" class="form-control" />
                </div>
                <button name="login" type="submit" class="btn btn-secondary mt-2">Login</button>
                <a href="register-cms.php"><button name="register" type="button" class="btn btn-secondary mt-2">Register</button></a>
                <a href="forgot-cms.php"><button name="forgot" type="button" class="btn btn-secondary mt-2">Forgot Password</button></a>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>