<?php
// Destroy session and redirect to home
session_destroy();
$_SESSION['toast_message'] = 'You have been logged out successfully.';
$_SESSION['toast_type'] = 'info';
redirect('index.php');
?>
