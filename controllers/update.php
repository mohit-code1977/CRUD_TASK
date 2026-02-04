<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
session_start();

if(!isset($_SESSION['update_id'])){
    header("Location:".BASE_URL. "/views/admin_dashboard.php");
}

// echo "update id : ". $_SESSION['update_id']. "<br/>";
$id = $_SESSION['update_id'];
// exit;

$error = [];
$name = $email = $mobile = $city = $role = "";
$isEdit = false;
$unique_Email = [];

$query = "SELECT * FROM users WHERE id= '$id'";
// echo "SQL Query : $query";
$result = $conn->query($query);
// exit();

if ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['phone'];
    $city = $row['city'];
    $role = $row['role'];
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['Register'])) {
    $name = trim($_POST['name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $mobile = trim($_POST['number'] ?? "");
    $city = trim($_POST['city'] ?? "");
    $role = $_POST['role'] ?? "";

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

    /*-----------City Validation------------*/
    if (empty($city)) {
        $error['city'] = "City is required !";
    } elseif (!preg_match("/^[a-zA-Z ]{2,50}$/", $city)) {
        $error['city'] = "Invalid city name";
    }

    /* ---- Email Uniqueness Check ---- */
    if (empty($error)) {

        $f_Email = $conn->real_escape_string($email);
        $f_Id    = $id;

        $sql = "SELECT id FROM users WHERE email = '$f_Email' AND id != $f_Id LIMIT 1";

        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            $error['email'] = "This email is already registered!";
        }
    }

    /*------------Updation-------------*/
    if (empty($error)) {
        $sql = "UPDATE users 
                SET name='$name', email='$email', phone='$mobile', city='$city', role='$role'
                WHERE ID='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
        alert('Update Successfully!');
      </script>";
      unset($_SESSION['update_id']);
      header("Location:".BASE_URL."/views/admin_dashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Page</title>
    <style>
        table,
        th,
        td,
        tr {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }

        button {
            margin-top: 10px;
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="page">
        <h2>User Registration Form</h2>
        <form method="POST" novalidate>
            <input type="hidden" name="edit_id" value="<?= $editId ?>">
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
                <lable> Mobile Number : </lable>
                <input type="text" name="number" value="<?= htmlspecialchars($mobile) ?>">
                <p style="color: red;" class="error_msg"><?= $error['number'] ?? "" ?></p>
            </div>

            <div class="div">
                <lable> City : </lable>
                <input type="text" name="city" value="<?= htmlspecialchars($city) ?>">
                <p style="color: red;" class="error_msg"><?= $error['city'] ?? "" ?></p>
            </div>

            <div class="div">
                <lable> Role : </lable>
                <select name="role" id="">
                    <option value="Admin" <?= $role == "Admin" ? "selected" : "" ?>>Admin</option>
                    <option value="Student" <?= $role == "Student" ? "selected" : "" ?>>Student</option>
                </select>
            </div>

            <button type="submit" name="Register" class="btn">Update Now</button>
        </form>
    </div>
    </div>
</body>

</html>