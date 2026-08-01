<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize_input($conn, $_POST['title']);
    $message = sanitize_input($conn, $_POST['message']);

    $insert_sql = "INSERT INTO announcements (title, message) VALUES ('$title', '$message')";
    mysqli_query($conn, $insert_sql);
}

$result = mysqli_query($conn, "SELECT * FROM announcements ORDER BY date_posted DESC");
?>

<div class="container">
    <h2>Publish Announcements</h2>
    <p>Post important notices for students.</p>
    <br>

    <form method="POST" action="announcements.php" style="background: #eee; padding: 15px; border-radius: 5px;">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="message" rows="4" style="width: 100%; padding: 10px;" required></textarea>
        </div>
        <button type="submit" class="btn">Publish Notice</button>
    </form>
    <br><hr><br>

    <h3>Past Announcements</h3>
    <ul>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <li style="margin-bottom: 10px;">
                <b><?php echo $row['title']; ?></b> (<?php echo $row['date_posted']; ?>)<br>
                <?php echo $row['message']; ?>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php require_once '../includes/footer.php'; ?>