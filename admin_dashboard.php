<?php
require("./db.php");

$isEdit = false;
$editId = "";

session_start();
$_SESSION['flag'] = true;

if (!isset($_SESSION['email'])) {
        header("Location:login.php");
        exit();
}

if($_SESSION['role'] !== "Admin"){
    header("Location:login.php");
    exit();
}


$sql = "Select * from users ";

$result = $conn->query($sql);

if($_SERVER['REQUEST_METHOD'] === "POST"){
 $id = (int)$_POST['id'] ?? "";

/*------------Delete Operation------------*/  
    if ($_POST['action'] === "Delete") {
        $conn->query("DELETE FROM users WHERE id='$id'");
        header("Location: admin_dashboard.php");
        exit();
    }

       if ($_POST['action'] === "Update") {
        $_SESSION['id'] = $id;
        header("Location:update.php");
        exit();
       }
}


/*----------LogOut----------*/
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location:login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table, th, tr, td{
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }
        .btn{
            cursor: pointer;
            /* border: 1px solid black;
            padding: 4px 25px; */
        }

        .btn1{
            cursor: pointer;
            border: 1px solid black;
            margin-top: 10px;
            padding: 4px 25px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <h1>Welcome To Admin dashboard</h1>
    <table>
       
<?php

if($result->num_rows>0){?>
 <tr>
            <th>Name </th>
            <th>Email</th>
            <th>Password</th>
            <th>Number</th>
            <th>City</th>
            <th>Role</th>
            <th>Operations</th>
        </tr>

  <?php  while($row = $result->fetch_assoc()){?>
        <tr>
            <td><?= $row['name'] ?></td>
             <td><?= $row['email'] ?></td>
              <td><?= $row['password'] ?></td>
               <td><?= $row['phone'] ?></td>
                <td><?= $row['city'] ?></td>
                <td><?= $row['role'] ?></td>

                 <td>
                                <form class="btn_form" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                                    <!-- update and delete button -->
                                    <input type="submit" class="btn" name="action" value="Update">
                                    <input type="submit" class="btn" name="action" value="Delete">
                                </form>
                            </td>
        </tr>
   <?php }
}
else{?>
<h4>There Is No Records Found</h4>
<?php }
?>
    </table>

    <form method="POST">
        <input type="submit" name="logout" class="btn1" value="LogOut">
    </form>
</body>
</html>