<?php
require("../config/db.php");

session_start();
$loginFlag = false;

$error = [];
$unique_Email = [];
$name = $email = $psw = $mobile = $city = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = trim($_POST['name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $psw = $_POST['password'] ?? "";
    $mobile = trim($_POST['number'] ?? "");
    $city = trim($_POST['city'] ?? "");


    /*-------------Name Validation------------*/
    if (empty($name)) {
        $error['name'] = "Name is required !";
    } elseif (!preg_match("/^[a-zA-Z ]{3,50}$/", $name)) {
        $error['name'] = "Name must contain only letters and spaces (3–50 chars)";
    }

    /*------------Email Validation-------------*/
    if (empty($email)) {
        $error['email'] = "Email is required !";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Invalid email address";
    }

    /*-----------Number Validation------------*/
    if ($mobile === "") {
        $error['number'] = "Mobile Number is required";
    } elseif (!preg_match("/^[6-9]\d{9}$/", $mobile)) {
        $error['number'] = "Invalid mobile number";
    }

    /*-----------Password Validation-----------*/
    if (empty($psw)) {
        $error['password'] = "Password is required !";
    } elseif (
        strlen($psw) < 8 ||
        !preg_match('/[A-Z]/', $psw) ||
        !preg_match('/[a-z]/', $psw) ||
        !preg_match('/[0-9]/', $psw) ||
        !preg_match('/[\W_]/', $psw)
    ) {
        $error['password'] = "Min 8 chars with upper, lower, number & symbol";
    }

    /*-----------City Validation------------*/
    if (empty($city)) {
        $error['city'] = "City is required !";
    } elseif (!preg_match("/^[a-zA-Z ]{2,50}$/", $city)) {
        $error['city'] = "Invalid city name";
    }


    /* ---- Email Uniqueness Check ---- */
    if (empty($error)) {
        $checkEmail = "SELECT email FROM users WHERE email = '$email'";
        $res = $conn->query($checkEmail);


        if ($res && $res->num_rows > 0) {
            $error['email'] = "This email is already registered!";
        }else{
            $sql = "INSERT INTO USERS (name, email, password, phone, city) VALUE 
            ('$name', '$email', '$psw', '$mobile', '$city')";

            if($conn->query($sql)){
                echo "
                <script>alert('Data Inserted Successfully');</script>";
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $psw;
                $_SESSION['role'] = "Student";
                $loginFlag = true;
                $_SESSION['flag'] = $loginFlag;
                $name = $email = $psw = $mobile = $city = "";
                header("Location:dashboard.php");
                exit();
            }else{
                echo "Insertion Failed : ". $conn->connect_errno;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
       <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css"  rel="stylesheet" />
    <style>
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            width: 100%;
        }

        #page {
            height: 100vh;
            width: 100%;
            /* padding: 20px; */
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

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
            left: 823px;
            opacity: 0.7;
        }

        .hide{
            display: none;
        }

        form {
            margin-top: 20px;
            height: 80%;
            width: 30%;
            display: flex;
            flex-direction: column;
            text-align: center;
            align-items: center;
            justify-content: center;
            gap: 30px;
            border: 1px solid black;
            border-radius: 20px;
        }

        .btn {
            padding: 4px 40px;
            border-radius: 20px;
            border: 1px solid black;
            cursor: pointer;
            background-color: #282e3342;
        }

        form div input {
            outline: none;
            border: 1px solid black;
            /* border-radius: 20px; */
            padding: 3px 10px;
        }

        a{
            /* text-decoration: none; */
            color: black;
            border: 1px solid black;
            background-color: #282e3342;
        }
    </style>
</head>

<body>
    <div id="page">
        <h2>User Registration Form</h2>
        <form method="POST" novalidate>
            <div class="div">
                <lable> Name : </lable>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
                <p style="color: red;" class="error_msg"><?= $error['name'] ?? "" ?></p>
            </div>

            <div class="div">
                <lable> Email : </lable>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
                <p style="color: red;" class="error_msg"><?= $error['email'] ?? "" ?></p>
            </div>

            <div class="div">
                <lable> Password : </lable>
                <input class="psw" type="password" name="password">
                <i id="mask" class='ri-eye-line icon'></i>
        <i id="mask_off" class='ri-eye-off-line icon hide'></i>
                <p style="color: red;" class="error_msg"><?= $error['password'] ?? "" ?></p>
            </div>

            <div class="div">
                <lable> Mobile Number : </lable>
                <input type="text" name="number" value="<?= htmlspecialchars($mobile) ?>">
                <p style="color: red;" class="error_msg"><?= $error['number'] ?? "" ?></p>
            </div>

            <div class="div">
                <lable> City : </lable>
                <input type="text" name="city" value="<?= htmlspecialchars($city) ?>">
                <p style="color: red;" class="error_msg"><?= $error['city'] ?? "" ?></p>
            </div>

            <button class="btn" value="register">Register</button>
            <!-- <br><br> -->
            <p>Already have an account : <a class="btn" href="./login.php">Login</a></p>
        </form>
    </div>

    <script src="script.js"></script>
</body>

</html>