<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2><p>You must be an Administrator to view this page.</p></div>");
}
?>

<div class="container">
    <h2>Administrator Dashboard</h2>
    <p>Welcome to the central control panel.</p>
    <br>
    
    <ul>
        <li><a href="manage_scholarships.php">Add / Manage Scholarship Programs</a></li>
        <li><a href="manage_applications.php">Review Student Applications</a></li>
        <li><a href="announcements.php">Publish Announcements</a></li>
        <li><a href="reports.php">Generate System Reports</a></li>
    </ul>
</div>

<?php require_once '../includes/footer.php'; ?>