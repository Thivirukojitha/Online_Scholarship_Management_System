<?php
require_once 'includes/header.php';
?>

<div class="container">
    <?php if(isset($_SESSION['user_id'])): ?>
        <h2>Welcome back, <?php echo $_SESSION['username']; ?>!</h2>
        <p>You are logged in as an <b><?php echo ucfirst($_SESSION['role']); ?></b>.</p>
        <br>
        
        <?php if($_SESSION['role'] == 'admin'): ?>
            <p>From the admin dashboard, you can manage scholarships, review applications, and publish announcements.</p>
            <a href="admin/dashboard.php" class="btn">Go to Admin Dashboard</a>
        <?php else: ?>
            <p>From your dashboard, you can browse available scholarships, apply, and check your application status.</p>
            <a href="student/dashboard.php" class="btn">Go to My Dashboard</a>
        <?php endif; ?>

    <?php else: ?>
        <h2>Welcome to the Online Scholarship Management System</h2>
        <p>This system allows students to easily apply for financial aid and helps administrators manage the evaluation process efficiently.</p>
        <br>
        <p>Please <a href="index.php">login</a> to continue.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>