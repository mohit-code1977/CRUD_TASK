<?php
require_once __DIR__ . '/../auth/session.php';
require_once __DIR__ . '/../config/db.php';

$sess_name = $_SESSION['name'] ?? "";

$sql = "Select * from users where role='Student' and name!='$sess_name'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <div id="main">
        <div class="heading">
        <h1>Welcome To Student Dashboard</h1>
       <div class="second_heading">
         <p class="name"><?= $sess_name ?></p>
        <a href="<?= BASE_URL ?>/auth/logout.php" class="btn">Logout</a>
       </div>
        </h1>
        
    </div>
    <table>

        <?php

        if ($result->num_rows > 0) { ?>
            <tr>
                <th>Name </th>
                <th>Email</th>
                <th>Number</th>
                <th>City</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['city'] ?></td>
                </tr>
            <?php }
        } else { ?>
            <h4>There Is No Records Found</h4>
        <?php }
        ?>
    </table>
    </div>
</body>

</html>