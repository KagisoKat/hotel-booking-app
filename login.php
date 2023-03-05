<!-- login page for both members and librarians -->

<?php
session_start();
require_once('./lib/autoloader.php');

if (isset($_POST['login'])) {
    require('./config/db.php');

    $userEmail = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
    $userPassword = filter_var($_POST["userPassword"], FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_email = ?');
    $stmt->execute([$userEmail]);
    $user_item = $stmt->FETCH();
    $user = new HotelClasses\User();
    if (isset($user_item)) {
        $user->setId($user_item->user_id);
        $user->setTitle($user_item->user_title);
        $user->setFirstName($user_item->user_firstname);
        $user->setLastName($user_item->user_lastname);
        $user->setEmail($user_item->user_email);
        $user->setAddress($user_item->user_address);
        $user->setPasswordHashed($user_item->user_password);

        if (password_verify($userPassword, $user->getPasswordHashed())) {
            echo "Password correct";
            $_SESSION['userId'] = $user->getId();
            $_SESSION['userType'] = "user";
            header('Location: index.php');
        } else {
            $loginWrong = "Email or password is incorrect";
        }
    }

}
require('./includes/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">User Login</div>
        <div class="card-body">
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input required type="email" name="userEmail" class="form-control" />
                    <br />
                    <?php if (isset($loginWrong)) { ?>
                        <p>
                            <?php echo $loginWrong ?>
                        <p>
                        <?php } ?>
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input required type="password" name="userPassword" class="form-control" />
                </div>
                <button name="login" type="submit" class="btn btn-primary mt-2">Login</button>
                <a href="register.php"><button name="register" type="button" class="btn btn-primary mt-2">Register</button></a>
                <a href="forgot.php"><button name="forgot" type="button" class="btn btn-primary mt-2">Forgot Password</button></a>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>