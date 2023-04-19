<?php
    session_start();
    require_once('./lib/autoloader.php');

    if (isset($_POST['register'])) {
        require('./config/db.php');

        $staff = new HotelClasses\Staff();
        $staff->setFirstName($_POST["staffFirstName"], FILTER_SANITIZE_STRING);
        $staff->setLastName($_POST["staffLastName"], FILTER_SANITIZE_STRING);
        $staff->setEmail($_POST["staffEmail"], FILTER_SANITIZE_EMAIL);
        $staff->setPassword($_POST["staffPassword"], FILTER_SANITIZE_STRING);
        $staff->hashPassword();
   
        $stmt = $pdo->prepare('SELECT * FROM staff WHERE staff_email = ?');
        $stmt->execute([$staff->getEmail()]);
        $totalUsers = $stmt->rowCount();
    
        if ($totalUsers > 0) {
            $emailTaken = "Email already been taken";
        } else {
            $stmt = $pdo->prepare('INSERT INTO staff (staff_firstname, staff_lastname, staff_email, staff_password) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $staff->getFirstName(), 
                $staff->getLastName(), 
                $staff->getEmail(), 
                $staff->getPasswordHashed(), 
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
            <form action="register-cms.php" method="POST">
                <div class="form-group">
                    <label for="staffFirstName">First Name</label>
                    <input required type="text" name="staffFirstName" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="staffLastName">Last Name</label>
                    <input required type="text" name="staffLastName" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="staffEmail">Email</label>
                    <input required type="email" name="staffEmail" class="form-control" />
                    <br />
                    <?php if (isset($emailTaken)) { ?>
                        <p>
                            <?php echo $emailTaken ?>
                        </p>
                        <?php }
                    $emailTaken ?>
                </div>
                <div class="form-group">
                    <label for="staffPassword">Password</label>
                    <input required type="password" name="staffPassword" class="form-control" />
                </div>
                <br />
                <button name="register" type="submit" class="btn btn-dark">Register</button>
                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button name="register" type="button" class="btn btn-dark">Back</button>
            </form>
        </div>
    </div>
</div>

<?php require('./includes/footer.html'); ?>