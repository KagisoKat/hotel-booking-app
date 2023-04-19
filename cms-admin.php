<!-- for librarians to login and view books and authors, etc -->

<?php

    session_start();
    
    require_once('./lib/autoloader.php');
    require('./lib/get-user-details.php');
    require('./config/db.php');
    
    if ($userType!='cms')
        header("Location: index.php");

    if (!isset($_GET["sorting"])) {
        $cmsSorting = "id";
    } else {
        $cmsSorting = $_GET['sorting'];
    }

    if (isset($_POST['search'])) {
        $searchString = "%" . filter_var($_POST["searchText"], FILTER_SANITIZE_STRING) . "%";
        $cmsQuery = 'SELECT * FROM staff WHERE staff.staff_firstname LIKE :ss OR staff.staff_lastname LIKE :ss OR staff.staff_email LIKE :ss OR staff.staff_id LIKE :ss';
    } else {
        $cmsQuery = 'SELECT * FROM staff';
    }

    if ($cmsSorting == 'id') {
        $cmsQuery .= " ORDER BY staff.staff_id";
    } elseif ($cmsSorting == 'fname') {
        $cmsQuery .= " ORDER BY staff.staff_firstname";
    } elseif ($cmsSorting == 'lname') {
        $cmsQuery .= " ORDER BY staff.staff_lastname";
    } elseif ($cmsSorting == 'email') {
        $cmsQuery .= " ORDER BY staff.staff_email";
    }
    
    if (isset($_POST['search'])) {
        $stmt = $pdo->prepare($cmsQuery);
        $stmt->bindValue(':ss', $searchString);
    } else {
        $stmt = $pdo->prepare($cmsQuery);
    }
    $stmt->execute();

    $allStaff = $stmt->fetchAll();
?>
<?php require('./includes/header.html'); ?>
<div class="container">

    <div class="content">
        <form method="post" name="searchForm" action="cms-admin.php">
            <input type="text" name="searchText" class="form-control mt-2" />
            <button name="search" type="submit" class="btn btn-dark mt-3 mb-2">Search</button>
        </form>
    </div>
    <div>
        <a href="cms-register-cms.php"><button name="registercms" type="button" class="btn btn-dark mt-3 mb-3">Register Staff</button></a>
    </div>
    <div>
        <p>Sorting:
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=id">ID</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=fname">First Name</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=lname">Last Name</a>
            <a href="<?php $_SERVER['PHP_SELF']; ?>?sorting=email">Email</a>
        </p>
    </div>
    <div>

        <table border="1" width="100%">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>


        <?php
        // output data of each row
        foreach ($allStaff as $staff_item) {
            $oneStaff = new HotelClasses\User();
            $oneStaff->setId($staff_item->staff_id);
            $oneStaff->setFirstName($staff_item->staff_firstname);
            $oneStaff->setLastName($staff_item->staff_lastname);
            $oneStaff->setEmail($staff_item->staff_email);
            echo "<tr>";
            echo "<td>" . $oneStaff->getId() . "</td>";
            echo "<td>" . $oneStaff->getFirstName() . "</td>";
            echo "<td>" . $oneStaff->getLastName() . "</td>";
            echo "<td>" . $oneStaff->getEmail() . "</td>";
            echo '<td><a href="cms-editcms.php?cms_id=' . $oneStaff->getId() . '">Edit</a></td>';
            echo '<td><a href="cms-deletecms.php?cms_id=' . $oneStaff->getId() . '">Delete</a></td>';
            echo "</tr>";
        }
        ?>
        </table>
    </div>
</div>

<?php require('./includes/footer.html'); ?>