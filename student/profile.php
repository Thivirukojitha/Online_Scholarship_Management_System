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
    $new_password = sanitize_input($conn, $_POST['new_password']);
    
    $update_sql = "UPDATE users SET password = '$new_password' WHERE id = $user_id";
    if(mysqli_query($conn, $update_sql)) {
        $success = "Password updated successfully!";
    } else {
        $error = "Error updating password.";
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
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>
        <button type="submit" class="btn">Update Password</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>