<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

$success = '';
$error = '';
$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = sanitize_input($conn, $_POST['old_password']);
    $new_password = sanitize_input($conn, $_POST['new_password']);
    $confirm_password = sanitize_input($conn, $_POST['confirm_password']);

    if($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        $check_sql = "SELECT password FROM users WHERE id = $user_id LIMIT 1";
        $result = mysqli_query($conn, $check_sql);

        if($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if($old_password !== $user['password']) {
                $error = "Old password is incorrect.";
            } else {
                $update_sql = "UPDATE users SET password = '$new_password' WHERE id = $user_id";
                if(mysqli_query($conn, $update_sql)) {
                    $success = "Password updated successfully!";
                } else {
                    $error = "Error updating password.";
                }
            }
        } else {
            $error = "Unable to verify current password.";
        }
    }
}
?>

<div class="container">
    <h2>My Profile</h2>
    <p>Manage your personal details here.</p>
    <br>

    <h3>Account Information</h3>
    <p><b>Username:</b> <?php echo $_SESSION['username']; ?></p>
    <p><b>Role:</b> <?php echo ucfirst($_SESSION['role']); ?></p>
    <br><hr><br>

    <h3>Change Password</h3>
    <?php if($error != '') echo "<div class='error'>$error</div>"; ?>
    <?php if($success != '') echo "<div style='color: green; margin-bottom: 10px;'>$success</div>"; ?>
    
    <form method="POST" action="profile.php">
        <div class="form-group">
            <label for="old_password">Old Password</label>
            <input type="password" name="old_password" id="old_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn">Update Password</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>