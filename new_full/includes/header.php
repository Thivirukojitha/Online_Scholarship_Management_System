<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Scholarship Management System</title>
    <link rel="stylesheet" href="/Online_Scholarship_Management_System/assets/css/style.css">
</head>
<body>
    <header>
        <h2>Scholarship Portal</h2>
        <nav>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="/Online_Scholarship_Management_System/home.php">Home</a>
                
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="/Online_Scholarship_Management_System/admin/dashboard.php">Admin Dashboard</a>
                <?php else: ?>
                    <a href="/Online_Scholarship_Management_System/student/dashboard.php">My Dashboard</a>
                <?php endif; ?>
                
                <a href="/Online_Scholarship_Management_System/functionalities.php">Features</a>
                <a href="/Online_Scholarship_Management_System/help.php">Help</a>
                <a href="/Online_Scholarship_Management_System/logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
            <?php else: ?>
                <a href="/Online_Scholarship_Management_System/index.php">Login</a>
                <a href="/Online_Scholarship_Management_System/functionalities.php">Features</a>
                <a href="/Online_Scholarship_Management_System/help.php">Help</a>
            <?php endif; ?>
        </nav>
    </header>