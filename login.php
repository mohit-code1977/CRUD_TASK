<?php
require("./db.php");
session_start();

$error = [];
$email = $password = "";

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if(empty($email)){
        $error['email'] = "Email is required ";
    }

    if(empty($password)){
        $error['psw'] = "Password is required";
    }

    /*-----------Login Logic----------*/
    if(empty($error)){
        $sql = "select * from users where email = '$email'";

        $result = $conn->query($sql);
        // print_r($result);
        // print("\n");

        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){        
                $db_email = $row['email'];
                $db_psw = $row['password'];

                echo "DB_Email : $db_email and Email : $email\n";
                echo "Db_psw : $db_psw and Password : $password";

               if($db_email===$email && $db_psw===$password){

               //-----------role checking----------
               if($row['role'] ==="Admin"){
                header("Location:admin_dashboard.php");
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $db_email;
                $_SESSION['password'] = $db_psw;
                $_SESSION['flag'] = true;
                $_SESSION['role'] = "Admin";
                exit();
               }
               else{
                header("Location:dashboard.php");
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $db_email;
                $_SESSION['password'] = $db_psw;
                $_SESSION['flag'] = true;
                $_SESSION['role'] = "Student";
                exit();
               }

               }
               else{
                echo "Credential Wrong";
               }
            }
        }
        else {
            echo "There is now Records found";
        }
    }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
      <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css"  rel="stylesheet" />
    <style>
        .icon{
            margin-top: 2px;
            cursor: pointer;
            font-size: 15px;
        }

        form a{
            text-decoration: none;
            color: #000;
        }

        #mask, #mask_off{
            position: absolute; 
            left: 160px;
            opacity: 0.7;
        }

        .hide{
            display: none;
        }
        </style>
</head>
<body>
    <form method="POST" novalidate>
        <label for="">Email ID:</label><br>
        <input type="email" name="email" >
        <p style="color: red;"><?= $error['email'] ?? ""?></p>
<!-- <br> -->
<!-- <br> -->

  <label for="">Password :</label><br>
  <input class="psw" type="password" name="password" >
   <i id="mask" class='ri-eye-line icon'></i>
        <i id="mask_off" class='ri-eye-off-line icon hide'></i>
  <p style="color: red;"><?= $error['psw'] ?? ""?></p>
  <!-- <br>
  <br> -->

  <button name="login">Login</button>
  <button name="sign_up"><a href="registration.php">Register</a></button>

    </form>
    <script src="script.js"></script>
</body>
</html>