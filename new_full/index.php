<?php
require_once 'includes/db_connect.php';
require_once 'includes/header.php';

if(isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);

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
    <p><i>Hint: Default user is username: <b>ucsc</b>, password: <b>ucsc</b></i></p>
</div>

<?php require_once 'includes/footer.php'; ?>