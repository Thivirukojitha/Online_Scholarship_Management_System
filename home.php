<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

$notice_result = mysqli_query($conn, "SELECT * FROM announcements ORDER BY date_posted DESC LIMIT 5");
?>

<div class="container">
    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="home-hero">
            <div class="hero-text">
                <h2>Welcome back, <?php echo $_SESSION['username']; ?>!</h2>
                <p>You are logged in as an <b><?php echo ucfirst($_SESSION['role']); ?></b>.</p>
                <p><?php echo ($_SESSION['role'] == 'admin') ? 'From the admin dashboard, you can manage scholarships, review applications, and publish announcements.' : 'From your dashboard, you can browse available scholarships, apply, and check your application status.'; ?></p>
                <a href="<?php echo ($_SESSION['role'] == 'admin') ? 'admin/dashboard.php' : 'student/dashboard.php'; ?>" class="btn"><?php echo ($_SESSION['role'] == 'admin') ? 'Go to Admin Dashboard' : 'Go to My Dashboard'; ?></a>
            </div>
        </div>
    <?php else: ?>
        <div class="home-hero">
            <div class="hero-text">
                <h2>Welcome to the Online Scholarship Management System</h2>
                <p>This system allows students to easily apply for financial aid and helps administrators manage the evaluation process efficiently.</p>
                <p>Please <a href="index.php">login</a> to continue.</p>
            </div>
        </div>
    <?php endif; ?>

    <hr>
    <h3>Notice Board</h3>
    <?php if($notice_result && mysqli_num_rows($notice_result) > 0): ?>
        <div class="notice-board">
            <?php while($notice = mysqli_fetch_assoc($notice_result)): ?>
                <div class="announcement-card">
                    <div>
                        <strong><?php echo htmlspecialchars($notice['title']); ?></strong>
                        <span class="notice-date"><?php echo htmlspecialchars($notice['date_posted']); ?></span>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($notice['message'])); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No notices available at this time.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>