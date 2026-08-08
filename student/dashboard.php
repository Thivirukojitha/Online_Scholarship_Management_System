<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2><p>You must be a Student to view this page.</p></div>");
}

$student_id = $_SESSION['user_id'];
$applications = array();
$app_error = '';

$app_query = "SELECT a.id, s.title AS scholarship_title, a.status, a.applied_date FROM applications a JOIN scholarships s ON a.scholarship_id = s.id WHERE a.student_id = $student_id ORDER BY a.applied_date DESC";
$app_result = mysqli_query($conn, $app_query);
if($app_result) {
    while($row = mysqli_fetch_assoc($app_result)) {
        $applications[] = $row;
    }
} else {
    $app_error = "Unable to load application status. Please try again later.";
}
?>

<div class="container">
    <h2>Student Dashboard</h2>
    <p>Manage your profile and applications here.</p>
    <br>
    
    <ul>
        <li><a href="profile.php">My Profile</a></li>
        <li><a href="browse_scholarships.php">Browse & Apply for Scholarships</a></li>
        <!--<li><a href="apply.php">Upload Documents</a></li>-->
    </ul>

    <br><hr><br>

    <h3>My Applications</h3>
    <?php if($app_error != ''): ?>
        <div class="error"><?php echo $app_error; ?></div>
    <?php elseif(empty($applications)): ?>
        <p>You have no submitted applications yet. Once you submit documents, your application status will appear here.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Scholarship</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($applications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['scholarship_title']); ?></td>
                        <td><?php echo htmlspecialchars($application['status']); ?></td>
                        <td><?php echo htmlspecialchars($application['applied_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>