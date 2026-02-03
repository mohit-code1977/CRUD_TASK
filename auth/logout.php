<?php
session_start();
session_unset();
session_destroy();

header("Location: /CRUD_TASK/auth/login.php");
exit;
?>