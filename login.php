<!-- login page for both members and librarians -->

<?php
session_start();
require_once('./lib/autoloader.php');

if (isset($_POST['login'])) {
    require('./config/db.php');

    $userEmail = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$userEmail]);
    $user_item = $stmt->FETCH();
    $user = new Library\User();
    if (isset($user_item)) {
        $user->setId($user_item->id);
        $user->setName($user_item->name);
        $user->setEmail($user_item->email);
        $user->setPassword($user_item->password);
        $user->setRole($user_item->role);
        if (password_verify($password, $user->getPassword())) {
            echo "Password correct";
            $_SESSION['userId'] = $user->getId();
            $_SESSION['userType'] = $user->getRole();
            $_SESSION['userName'] = $user->getName();
            header('Location: index.php');
        } else {
            $loginWrong = "Email or password is incorrect";
        }
    }

}
require('./inc/header.html'); ?>

<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Login</div>
        <div class="card-body">
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="userEmail">User Email</label>
                    <input required type="email" name="userEmail" class="form-control" />
                    <br />
                    <?php if (isset($loginWrong)) { ?>
                        <p>
                            <?php echo $loginWrong ?>
                        <p>
                        <?php } ?>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input required type="password" name="password" class="form-control" />
                </div>
                <button name="login" type="submit" class="btn btn-primary mt-2">Login</button>
                <a href="register.php"><button name="register" type="button" class="btn btn-primary mt-2">Register</button></a>
                <a href="forgot.php"><button name="forgot" type="button" class="btn btn-primary mt-2">Forgot Password</button></a>
            </form>
        </div>
    </div>
</div>

<?php require('./inc/footer.html'); ?>