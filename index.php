<?php
    session_start();
    require_once('./lib/autoloader.php');

    if(isset($_SESSION['userId'])) {
        require('./config/db.php');

        $userId = $_SESSION['userId'];

        $stmt = $pdo -> prepare('SELECT * FROM users WHERE id = ? ');
        $stmt -> execute( [ $userId ]);

        $user_item = $stmt ->fetch();

        $user = new Library\User();
        $user->setId($user_item->id);
        $user->setName($user_item->name);
        $user->setEmail($user_item->email);
        $user->setRole($user_item->role);

        if ($user->getRole() === 'guest' ) {
            $message = "Your role is guest";
        }
    }
?>

<?php require('./includes/header.html'); ?>

<div class="container">
    <div class= "card bg-light mb-3">
        <div class="card-header">
            <?php  if (isset($user)) { ?>
                <h5>Welcome <?php echo $user->getname() ?></h5>
            <?php } else { ?>
                <h5>Welcome Guest</h5>
            <?php } ?>
        </div>
        <div class="card-body">
            <h1>Show hotels here regardless of login status</h1>
            <?php  if (isset($user)) { ?>
                <h5>Only Logged in users can enjoy this content</h5>
            <?php } else { ?>
                <h4>Please Login/Register to unlock all the content</h4>
            <?php } ?>
        </div>
    </div>
</div>
<?php require('./includes/footer.html'); ?>