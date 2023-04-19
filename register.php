<?php
    require_once('./lib/autoloader.php');

    if (isset($_POST['register'])) {
        require('./config/db.php');

        $user = new HotelClasses\User();
        $user->setTitle($_POST["userTitle"], FILTER_SANITIZE_STRING);
        $user->setFirstName($_POST["userFirstName"], FILTER_SANITIZE_STRING);
        $user->setLastName($_POST["userLastName"], FILTER_SANITIZE_STRING);
        $user->setEmail($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
        $user->setAddress($_POST["userAddress"], FILTER_SANITIZE_STRING);
        $user->setPassword($_POST["userPassword"], FILTER_SANITIZE_STRING);
        $user->hashPassword();
   
        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_email = ?');
        $stmt->execute([$user->getEmail()]);
        $totalUsers = $stmt->rowCount();
    
        if ($totalUsers > 0) {
            $emailTaken = "Email already been taken";
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (user_title, user_firstname, user_lastname, user_email, user_address, user_password) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $user->getTitle(),
                $user->getFirstName(), 
                $user->getLastName(), 
                $user->getEmail(), 
                $user->getAddress(),
                $user->getPasswordHashed(), 
            ]);
            header('Location: thankyou.php');
        }
    }
?>
<?php require('./includes/header.html'); ?>


<div class="container">
    <div class="card">
        <div class="card-header bg-light mb-3">Register</div>
        <div class="card-body">
            <form action="register.php" method="POST">
                <div class="form-group">
                    <select name="userTitle">
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Miss.">Miss.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Prof.">Prof.</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="userFirstName">First Name</label>
                    <input required type="text" name="userFirstName" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="userLastName">Last Name</label>
                    <input required type="text" name="userLastName" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input required type="email" name="userEmail" class="form-control" />
                    <br />
                    <?php if (isset($emailTaken)) { ?>
                        <p>
                            <?php echo $emailTaken ?>
                        </p>
                        <?php }
                    $emailTaken ?>
                </div>
                <div class="form-group">
                    <label for="userAddress">Address</label>
                    <input required type="text" name="userAddress" class="form-control" />
                </div>

                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input required type="password" name="userPassword" class="form-control" />
                </div>
                <br />
                <button name="register" type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>