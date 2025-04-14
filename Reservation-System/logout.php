<?php
session_start();
session_destroy();
header("Location: index.php"); // Redirects to admin login page
exit();
?>