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

if(isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM announcements WHERE id = $delete_id");
    header("Location: announcements.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM announcements ORDER BY date_posted DESC");
?>

<div class="container">
    <h2>Publish Announcements</h2>
    <p>Post important notices for students.</p>
    <br>

    <form method="POST" action="announcements.php" style="background: #f5e7ef; padding: 15px; border-radius: 5px;">
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
            <li style="margin-bottom: 15px; padding: 12px; border: 1px solid #e0c8d8; border-radius: 6px; background: #fdf2f8;">
                <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: flex-start;">
                    <div>
                        <b><?php echo htmlspecialchars($row['title']); ?></b>
                        <span style="color: #555; font-size: 0.9rem;">(<?php echo $row['date_posted']; ?>)</span><br>
                        <span><?php echo nl2br(htmlspecialchars($row['message'])); ?></span>
                    </div>
                    <div>
                        <a href="announcements.php?delete=<?php echo $row['id']; ?>" style="color: #d00000; font-weight: bold;" onclick="return confirm('Delete this announcement?');">Delete</a>
                    </div>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php require_once '../includes/footer.php'; ?>