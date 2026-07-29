<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2><p>You must be a Student to view this page.</p></div>");
}
?>

<div class="container">
    <h2>Student Dashboard</h2>
    <p>Manage your profile and applications here.</p>
    <br>
    
    <ul>
        <li><a href="profile.php">My Profile</a></li>
        <li><a href="browse_scholarships.php">Browse & Apply for Scholarships</a></li>
        <li><a href="apply.php">Upload Documents</a></li>
    </ul>
</div>

<?php require_once '../includes/footer.php'; ?>