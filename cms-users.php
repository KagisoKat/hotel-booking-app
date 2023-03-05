<!-- for librarians to login and view books and authors, etc -->

<?php

    session_start();

    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if (! $userType=='cms')
    header("Location: index.php");

    if (isset($_POST['search'])) {
        $searchString = "%" . filter_var($_POST["searchText"], FILTER_SANITIZE_STRING) . "%";
        $stmt = $pdo->prepare('SELECT * FROM users WHERE users.user_firstname LIKE :ss OR users.user_lastname LIKE :ss OR users.user_email LIKE :ss OR users.user_address LIKE :ss OR users.user_id LIKE :ss');
        $stmt->bindValue(':ss', $searchString);
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users');
        $stmt->execute();
    }

    $allUsers = $stmt->fetchAll();
?>
<?php require('./includes/header.html'); ?>
<div class="container">

    <div class="content">
        <form method="post" name="searchForm" action="cms-users.php">
            <input type="text" name="searchText" class="form-control mt-2" />
            <button name="search" type="submit" class="btn btn-primary mt-3 mb-2">Search</button>
        </form>
    </div>
    <div>

        <table border="1" width="100%">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>


        <?php
        // output data of each row
        foreach ($allUsers as $user_item) {
            $oneUser = new HotelClasses\User();
            $oneUser->setId($user_item->user_id);
            $oneUser->setTitle($user_item->user_title);
            $oneUser->setFirstName($user_item->user_firstname);
            $oneUser->setLastName($user_item->user_lastname);
            $oneUser->setEmail($user_item->user_email);
            $oneUser->setAddress($user_item->user_address);
            echo "<tr>";
            echo "<td>" . $oneUser->getId() . "</td>";
            echo "<td>" . $oneUser->getTitle() . "</td>";
            echo "<td>" . $oneUser->getFirstName() . "</td>";
            echo "<td>" . $oneUser->getLastName() . "</td>";
            echo "<td>" . $oneUser->getEmail() . "</td>";
            echo "<td>" . $oneUser->getAddress() . "</td>";
            echo '<td><a href="cms-edituser.php?user_id=' . $oneUser->getId() . '">Edit</a></td>';
            echo '<td><a href="cms-deleteuser.php?user_id=' . $oneUser->getId() . '">Delete</a></td>';
            echo "</tr>";
        }
        ?>
        </table>
    </div>
</div>

<?php require('./includes/footer.html'); ?>