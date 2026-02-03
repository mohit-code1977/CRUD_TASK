<?php
require("./db.php");

$error = [];
$name = $email = $psw = $mobile = $city = $role = "";
$isEdit = false;
$editId = "";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['Register'])) {
    $name = trim($_POST['name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $psw = $_POST['password'] ?? "";
    $mobile = trim($_POST['number'] ?? "");
    $city = trim($_POST['city'] ?? "");
    $role = trim($_POST['role'] ?? "");
    $editId = $_POST['edit_id'] ?? "";


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

    /*-----------Role Validation------------*/
    if (empty($role)) {
        $error['role'] = "Role is required !";
    } elseif (!preg_match("/^[a-zA-Z ]{2,50}$/", $role)) {
        $error['role'] = "Invalid role name";
    }

    /* ---- Email Uniqueness Check ---- */
    if (empty($error)) {
        $checkEmail = "SELECT * FROM users WHERE email = '$email'";
        if ($editId !== "") {
            $checkEmail .= " AND ID != '$editId'";
        }

        $res = $conn->query($checkEmail);
        if ($res->num_rows > 0) {
            $error['email'] = "This email is already registered!";
        }
    }



    /* ---- INSERT / UPDATE ---- */
    if (empty($error)) {
        if ($editId !== "") {
            // UPDATE
            $sql = "UPDATE users 
                SET name='$name', email='$email', password='$psw', phone='$mobile', city='$city', role='$role'
                WHERE ID='$editId'";
        } else {
            // INSERT
            $sql = "INSERT INTO users (name, email, password, phone, city, role)
                VALUES ('$name', '$email', '$psw', '$mobile', '$city', '$role')";
        }

        if ($conn->query($sql)) {
            echo "<script>alert('Success!'); window.location.href='admin_dash.php';</script>";
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}


/* ---------- HANDLE UPDATE CLICK (EDIT MODE) ---------- */
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action'])) {

    $id = (int)$_POST['id'];

    if ($_POST['action'] === "Delete") {
        $conn->query("DELETE FROM users WHERE id='$id'");
        header("Location: admin_dash.php");
        exit();
    }

    if ($_POST['action'] === "Update") {
        $result = $conn->query("SELECT * FROM users WHERE id='$id'");
        if ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $email = $row['email'];
            $psw = $row['password'];
            $mobile = $row['phone'];
            $city = $row['city'];
            $role = $row['role'];

            $isEdit = true;
            $editId = $id;
        }
    }
}

/* ---------- FETCH TABLE DATA ---------- */
$data = [];
$result = $conn->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Page</title>
    <style>
        #page2 {
            position: absolute;
            right: 10%;
            top: 25%;
        }

        table,
        th,
        td,
        tr {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
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
                <lable> Password : </lable>
                <input type="password" name="password">
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

            <div class="div">
                <lable> Role : </lable>
                <input type="text" name="role" value="<?= htmlspecialchars($role) ?>">
                <p style="color: red;" class="error_msg"><?= $error['role'] ?? "" ?></p>
            </div>

            <button type="submit" name="Register" class="btn"><?= $isEdit ? "Update" : "Insert" ?></button>
        </form>
    </div>

    <div id="page2">
        <?php
        if (!empty($data)) { ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Mobile No.</th>
                    <th>City</th>
                    <th>Role</th>
                    <th>Operations</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    foreach ($data as $d) { ?>
                        <tr>
                            <td><?= $d['name'] ?></td>
                            <td><?= $d['email'] ?></td>
                            <td><?= $d['password'] ?></td>
                            <td><?= $d['phone'] ?></td>
                            <td><?= $d['city'] ?></td>
                            <td><?= $d['role'] ?></td>

                            <td>
                                <form class="btn_form" method="post" style="display:inline;">
                                    <!-- <input type="hidden" name="edit_id" value="<?= $editId ?>"> -->
                                    <input type="hidden" name="id" value="<?= $d['ID'] ?>">
                                    <!-- update and delete button -->
                                    <input type="submit" name="action" value="Update">
                                    <input type="submit" name="action" value="Delete">
                                </form>
                            </td>
                        </tr>
                <?php  }
                } ?>

            </table>

        <?php
        } else {
            echo "No data records found";
        }
        ?>

    </div>
    </div>

</body>

</html>