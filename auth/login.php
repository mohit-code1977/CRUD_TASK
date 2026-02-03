<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
session_start();

$error = [];
$email = $password = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $error['email'] = "Email is required ";
    }

    if (empty($password)) {
        $error['psw'] = "Password is required";
    }

  /*----------Login Validation----------*/
   if (empty($error)) {
    $sql = "SELECT name, email, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
         if ($password === $row['password']) {
            $_SESSION['name']  = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role']  = $row['role'];

            if ($row['role'] == "Admin") {
                // $_SESSION['flag'] = true;
                header("Location: " . BASE_URL . "/views/admin_dashboard.php");
                exit();
            } else {
                header("Location: " . BASE_URL . "/views/dashboard.php");
                exit();
            }

        } else {
            $error['psw'] = "Incorrect password";
        }

    } else {
        $error['email'] = "Email not found";
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
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        .icon {
            margin-top: 2px;
            cursor: pointer;
            font-size: 15px;
        }

        form a {
            text-decoration: none;
            color: #000;
        }

        #mask,
        #mask_off {
            position: absolute;
            left: 160px;
            opacity: 0.7;
        }

        .hide {
            display: none;
        }
    </style>
</head>

<body>
    <form method="POST" novalidate>
        <label for="">Email ID:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <p style="color: red;"><?= $error['email'] ?? "" ?></p>

        <label for="">Password :</label><br>
        <input class="psw" type="password" name="password">
        <i id="mask" class='ri-eye-line icon'></i>
        <i id="mask_off" class='ri-eye-off-line icon hide'></i>
        <p style="color: red;"><?= $error['psw'] ?? "" ?></p>

        <button name="login">Login</button>
        <button name="sign_up"><a href="<?= BASE_URL ?>/auth/registration.php">Register</a>
        </button>

    </form>
    <script src="<?= BASE_URL ?>/auth/script.js"></script>
</body>

</html>