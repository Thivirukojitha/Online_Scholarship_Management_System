<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

$total_students_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='student'");
$total_students_row = mysqli_fetch_assoc($total_students_result);
$total_students = $total_students_row['count'];

$total_scholarships_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM scholarships");
$total_scholarships_row = mysqli_fetch_assoc($total_scholarships_result);
$total_scholarships = $total_scholarships_row['count'];

$total_apps_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications");
$total_apps_row = mysqli_fetch_assoc($total_apps_result);
$total_apps = $total_apps_row['count'];

$approved_apps_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE status='Approved'");
$approved_apps_row = mysqli_fetch_assoc($approved_apps_result);
$approved_apps = $approved_apps_row['count'];

$rejected_apps_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE status='Rejected'");
$rejected_apps_row = mysqli_fetch_assoc($rejected_apps_result);
$rejected_apps = $rejected_apps_row['count'];
?>

<div class="container">
    <h2>System Reports</h2>
    <p>Overall statistics of the Online Scholarship Management System.</p>
    <br>

    <table border="1" style="width: 50%; border-collapse: collapse; text-align: left; background: #fff;">
        <tr>
            <th style="padding: 10px; background-color: #004080; color: white;">Metric</th>
            <th style="padding: 10px; background-color: #004080; color: white;">Count</th>
        </tr>
        <tr><td style="padding: 10px;">Registered Students</td><td style="padding: 10px;"><?php echo $total_students; ?></td></tr>
        <tr><td style="padding: 10px;">Available Scholarships</td><td style="padding: 10px;"><?php echo $total_scholarships; ?></td></tr>
        <tr><td style="padding: 10px;">Total Applications Submitted</td><td style="padding: 10px;"><?php echo $total_apps; ?></td></tr>
        <tr><td style="padding: 10px;">Approved Applications</td><td style="padding: 10px; color: green; font-weight: bold;"><?php echo $approved_apps; ?></td></tr>
        <tr><td style="padding: 10px;">Rejected Applications</td><td style="padding: 10px; color: red; font-weight: bold;"><?php echo $rejected_apps; ?></td></tr>
    </table>
    <br>
    <button onclick="window.print()" class="btn">Print Report</button>
</div>

<?php require_once '../includes/footer.php'; ?>