<?php
require("./db.php");

session_start();

if (!isset($_SESSION['email'])) {
        header("Location:login.php");
        exit();
}

if($_SESSION['role'] !== "Student"){
    header("Location:login.php");
    exit();
}


$sql = "Select * from users where role='Student'";

$result = $conn->query($sql);


if($_SERVER['REQUEST_METHOD'] ==="POST"){
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

        .btn {
            padding: 4px 40px;
            border-radius: 20px;
            border: 1px solid black;
            cursor: pointer;
            background-color: #282e3342;
        }

        .heading{
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;    

        }
    </style>
</head>
<body>
   <div class="heading">
    <h1>Welcome To Student dashboard</h1>
    <form method="POST">
        <input type="submit" class="btn" name="logout" value="LogOut">
    </form>
   </div>
    <table>
       
<?php

if($result->num_rows>0){?>
 <tr>
            <th>Name </th>
            <th>Email</th>
            <th>Password</th>
            <th>Number</th>
            <th>City</th>
        </tr>

  <?php  while($row = $result->fetch_assoc()){?>
        <tr>
            <td><?= $row['name'] ?></td>
             <td><?= $row['email'] ?></td>
              <td><?= $row['password'] ?></td>
               <td><?= $row['phone'] ?></td>
                <td><?= $row['city'] ?></td>
        </tr>
   <?php }
}
else{?>
<h4>There Is No Records Found</h4>
<?php }
?>
    </table>
</body>
</html>