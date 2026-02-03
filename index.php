<?php
echo "Hello";
exit;
$isEdit = false;
$editId = null;


/*----------Data Insertion-----------*/
$name = $email = $department = $salary = "";
$error = [];

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['register'])) {
    $editId = $_POST['edit_id'] ?? null;   //---decide about Updation & Deletion

    $name = $_POST['name'] ?? "";
    $email = $_POST['email'] ?? "";

    //-----Name Validation-----
    if (empty($name)) {
        $error['name'] = "Please enter your name";
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $error['name'] = "Only letters and white space allowed";
        }
    }

    //-----Email Validation------
    if (empty($email)) {
        $error['email'] = "Please enter your Email";
    } else {
        // if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //    $error['email'] = "Invalid email format";
        $regex = '/@gmail\\.com/is';
        if (!preg_match($regex, $email)) {
            $error['email'] = "Invalid Email Format";
        }
    }


  


    /*-----------------Database Connection----------------*/
    if (empty($error)) {
        $arr = [];
        $fetchQuery = "SELECT Email FROM EMPLOYEES";
        $result = $conn->query($fetchQuery);

        /*-----------------Database Connection----------------*/
        if (empty($error)) {
            $arr = [];
            $fetchQuery = "SELECT Email FROM employees";
            $result = $conn->query($fetchQuery);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $arr[] = $row['Email'];
                }
            }

            if ($editId) {
                $check = "SELECT id FROM employees 
              WHERE Email='$email' AND id != '$editId'";
                $res = $conn->query($check);

                if ($res && $res->num_rows > 0) {
                    $error['email'] = "Email already exists for another employee";
                } else {

                    $sql = "UPDATE employees SET 
                    Name='$name',
                    Email='$email',
                    Department='$department',
                    Salary='$salary'
                WHERE id='$editId'";

                    if ($conn->query($sql)) {
                        header("Location: index.php");
                        exit();
                    }
                }
            } else {

                if (!in_array($email, $arr)) {

                    $sql = "INSERT INTO employees (Name, Email, Department, Salary)
                    VALUES ('$name', '$email', '$department', '$salary')";

                    if ($conn->query($sql)) {
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    $error['email'] = "Email must be unique";
                }
            }
        }
    }
}



/*------------View Table Data----------------*/
$sql = "SELECT id, Name, Email, Department, Salary FROM employees";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}



/*---------------Interaction With Table Data--------------------*/
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    //---------delete row----------
    if ($action === "Delete") {
        $sql = "Delete FROM employees WHERE id='$id'";

        $conn->query($sql);
        header("Location: index.php");
        exit();
    }

    //---------update row----------
    if ($action === "Update") {
        $sql = "Select * FROM employees WHERE id='$id'";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $name = $row['Name'];
            $email = $row['Email'];
            $department = $row['Department'];
            $salary = $row['Salary'];

            $isEdit = true;
            $editId = $id;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD OPERATIONS</title>
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
            /* display: flex; */
        }

        form {
            margin: 20px;
            width: 25%;
            height: 60%;
            border: 1px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        #page2 {
            position: absolute;
            top: 100px;
            right: 200px;
        }

        table,
        th,
        td,
        tr {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }

        .btn_form {
            border: none;
            /* padding: 5px; */
        }

        .btn_form input {
            margin-right: 5px;
            cursor: pointer;
            padding: 2px;
            /* border-radius: 20px; */
        }

        .form_btn{
            padding: 2px 20px;
            cursor: pointer;
        }
        
    </style>
</head>

<body>
    <div id="main">
        <div id="page1">
            <!-- <h1>Data Form</h1> -->
            <form method="POST" novalidate>
                <input type="hidden" name="edit_id" value="<?= $editId ?>">

                <div class="name">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
                    <p style="color: red;" class="error_msg"><?= $error['name'] ?? '' ?> </p>
                </div>
                <br><br>

                <div class="email">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
                    <p style="color: red;" class="error_msg"><?= $error['email'] ?? '' ?> </p>
                </div>
                <br><br>

                <div class="department">
                    <label for="department">Department:</label>
                    <input type="text" name="department" value="<?= htmlspecialchars($department) ?>">
                    <p style="color: red;" class="error_msg"><?= $error['department'] ?? '' ?></p>
                </div>
                <br><br>

                <div class="salary">
                    <label for="salary">Salary:</label>
                    <input type="text" name="salary" value="<?= htmlspecialchars($salary) ?>">
                    <p style="color: red;" class="error_msg"><?= $error['salary'] ?? '' ?></p>
                </div>
                <br><br>

                <button class="form_btn" name="register">
                    <?= $isEdit ? 'Update' : '  Insert  ' ?>
                </button>
            </form>
        </div>


        <div id="page2">
           <?php 
           if(!empty($data)){ ?>
             <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Salary</th>
                    <th>Operations</th>
                </tr>

                <?php if (!empty($data)) {
                    foreach ($data as $d) { ?>
                        <tr>
                            <td><?php echo $d['Name'] ?? ""; ?></td>
                            <td><?php echo $d['Email'] ?? ""; ?></td>
                            <td><?php echo $d['Department'] ?? ""; ?></td>
                            <td><?php echo $d['Salary'] ?? ""; ?></td>
                            <td>
                                <form class="btn_form" method="post" style="display:inline;">
                                    <input type="hidden" name="edit_id" value="<?= $editId ?>">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
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
           }
           else{
            echo "No data records found";
           }
           ?>

        </div>
    </div>
</body>

</html>