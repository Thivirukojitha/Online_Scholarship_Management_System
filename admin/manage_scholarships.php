<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_scholarship'])) {
    $title = sanitize_input($conn, $_POST['title']);
    $description = sanitize_input($conn, $_POST['description']);
    $deadline = sanitize_input($conn, $_POST['deadline']);

    $insert_sql = "INSERT INTO scholarships (title, description, deadline) VALUES ('$title', '$description', '$deadline')";
    if(mysqli_query($conn, $insert_sql)) {
        $success = "Scholarship added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

if(isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM scholarships WHERE id = $del_id");
    header("Location: manage_scholarships.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM scholarships ORDER BY deadline ASC");
?>

<div class="container">
    <h2>Manage Scholarships</h2>
    <p>Add new scholarships or remove existing ones.</p>
    <br>

    <?php if($error != '') echo "<div class='error'>$error</div>"; ?>
    <?php if($success != '') echo "<div style='color: green; margin-bottom: 10px;'>$success</div>"; ?>

    <form method="POST" action="manage_scholarships.php" style="background: #eee; padding: 15px; border-radius: 5px;">
        <h3>Add New Scholarship</h3><br>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" required>
        </div>
        <div class="form-group">
            <label>Deadline</label>
            <input type="date" name="deadline" required>
        </div>
        <button type="submit" name="add_scholarship" class="btn">Add Program</button>
    </form>
    <br><hr><br>

    <h3>Existing Programs</h3><br>
    <?php if(mysqli_num_rows($result) > 0): ?>
        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <tr style="background-color: #004080; color: white;">
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">Deadline</th>
                <th style="padding: 10px;">Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="padding: 10px;"><?php echo $row['title']; ?></td>
                    <td style="padding: 10px;"><?php echo $row['deadline']; ?></td>
                    <td style="padding: 10px;">
                        <a href="manage_scholarships.php?delete=<?php echo $row['id']; ?>" style="color: red;" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No scholarships found.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>