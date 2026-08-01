<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

if(isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($conn, $_POST['username']);
    $password = sanitize_input($conn, $_POST['password']);
    $confirm_password = sanitize_input($conn, $_POST['confirm_password']);

    if($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $check_sql = "SELECT id FROM users WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_sql);

        if(mysqli_num_rows($check_result) > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            $insert_sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'student')";
            if(mysqli_query($conn, $insert_sql)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<div class="container">
    <h2>Student Registration</h2>
    <p>Create a new account to apply for scholarships.</p>
    <br>
    
    <?php if($error != ''): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if($success != ''): ?>
        <div style="color: green; margin-bottom: 10px;"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php" onsubmit="return validateRegistration()">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
    <br>
    <p>Already have an account? <a href="index.php">Login here</a>.</p>
</div>


<script src="assets/js/script.js"></script>

<?php require_once 'includes/footer.php'; ?>