<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Scholarship Management System</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <h2>Scholarship Portal</h2>
        <nav>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="/home.php">Home</a>
                
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="/admin/dashboard.php">Admin Dashboard</a>
                <?php else: ?>
                    <a href="/student/dashboard.php">My Dashboard</a>
                <?php endif; ?>
                
                <a href="/functionalities.php">Features</a>
                <a href="/help.php">Help</a>
                <a href="/logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
            <?php else: ?>
                <a href="/index.php">Login</a>
                <a href="/functionalities.php">Features</a>
                <a href="/help.php">Help</a>
            <?php endif; ?>
        </nav>
    </header>