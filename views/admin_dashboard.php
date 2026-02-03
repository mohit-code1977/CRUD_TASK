<?php
require_once __DIR__ . '/../auth/admin_session.php'; 
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';

$sql = "SELECT id, name, email, phone, city, role FROM users";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (!isset($_POST['id'], $_POST['action'])) {
        header("Location: " . BASE_URL . "/views/admin_dashboard.php");
        exit;
    }

    $id = (int) $_POST['id'];

    if ($_POST['action'] === "Delete") {
        $conn->query("DELETE FROM users WHERE id = $id");
        header("Location: " . BASE_URL . "/views/admin_dashboard.php");
        exit;
    }

    if ($_POST['action'] === "Update") {
        $_SESSION['update_id'] = $id;
        
        header("Location: " . BASE_URL . "/controllers/update.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }
        .btn {
            cursor: pointer;
        }
        .btn1 {
            cursor: pointer;
            border: 1px solid black;
            margin-top: 10px;
            padding: 6px 25px;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>

<h1>Welcome To Admin Dashboard</h1>

<table>
<?php if ($result && $result->num_rows > 0): ?>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Number</th>
            <th>City</th>
            <th>Role</th>
            <th>Operations</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['city']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="submit" class="btn" name="action" value="Update">
                    <input type="submit" class="btn" name="action" value="Delete"
                           onclick="return confirm('Are you sure you want to delete this user?');">
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
<?php else: ?>
    <tr>
        <td colspan="6">No records found</td>
    </tr>
<?php endif; ?>
</table>

<br>

<a href="<?= BASE_URL ?>/auth/logout.php" class="btn1">Logout</a>

</body>
</html>
