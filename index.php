<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

if(isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<div class="container">
    <h2>System Login</h2>
    <p>Please login to access the Online Scholarship Management System.</p>
    <br>
    
    <?php if($error != ''): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    <br>
    <!--<p><i>Hint: Default user is username: <b>ucsc</b>, password: <b>ucsc</b></i></p>-->
    <p>Don't have an account? <a href="register.php?role=student">Student Registration</a> | <a href="register.php?role=admin">Admin Registration</a></p>
</div>

<?php require_once 'includes/footer.php'; ?>