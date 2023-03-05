<?php
require_once('./lib/autoloader.php');

if (isset($_POST['reset'])) {
    require('./config/db.php');


    $user = new HotelClasses\User();
    $user->setEmail(filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL));
    $user->setPassword(filter_var($_POST["userPassword"], FILTER_SANITIZE_STRING));
    $user->hashPassword();

    if (filter_var($user->getEmail(), FILTER_SANITIZE_EMAIL)) {
        $stmt = $pdo->prepare('SELECT * from users WHERE user_email = ? ');
        $stmt->execute([$user->getEmail()]);
        $totalUsers = $stmt->rowCount();

        if ($totalUsers != 1) {
            $emailNotExist = "Email does not exist";
        } else {
            $stmt = $pdo->prepare('UPDATE users SET user_password = ? WHERE user_email = ?');
            $stmt->execute([$user->getPasswordHashed(), $user->getEmail()]);
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
            <form action="forgot.php" method="POST">
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input required type="email" name="userEmail" class="form-control" />
                    <br />
                    <?php if (isset($emailNotExist)) { ?>
                        <p>
                            <?php echo $emailNotExist ?>
                        <p>
                        <?php }
                    $emailEmailNotExist ?>
                </div>
                <div class="form-group">
                    <label for="userPassword">New Password</label>
                    <input required type="password" name="userPassword" class="form-control" />
                </div>
                <br />
                <button name="reset" type="submit" class="btn btn-primary">Reset</button>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>