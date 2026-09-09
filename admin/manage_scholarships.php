<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

$success = '';
$error = '';
$edit_scholarship = null;

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_scholarship'])) {
    $title = sanitize_input($conn, $_POST['title']);
    $description = sanitize_input($conn, $_POST['description']);
    $deadline = sanitize_input($conn, $_POST['deadline']);

    $insert_stmt = mysqli_prepare($conn, "INSERT INTO scholarships (title, description, deadline) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($insert_stmt, "sss", $title, $description, $deadline);
    if(mysqli_stmt_execute($insert_stmt)) {
        $success = "Scholarship added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_scholarship'])) {
    $scholarship_id = intval($_POST['scholarship_id']);
    $title = sanitize_input($conn, $_POST['title']);
    $description = sanitize_input($conn, $_POST['description']);
    $deadline = sanitize_input($conn, $_POST['deadline']);

    if($scholarship_id < 1 || $title === '' || $description === '' || $deadline === '') {
        $error = "All scholarship fields are required.";
    } else {
        $update_stmt = mysqli_prepare($conn, "UPDATE scholarships SET title = ?, description = ?, deadline = ? WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "sssi", $title, $description, $deadline, $scholarship_id);
        if(mysqli_stmt_execute($update_stmt)) {
            $success = "Scholarship updated successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

if(isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_stmt = mysqli_prepare($conn, "SELECT id, title, description, deadline FROM scholarships WHERE id = ?");
    mysqli_stmt_bind_param($edit_stmt, "i", $edit_id);
    mysqli_stmt_execute($edit_stmt);
    $edit_result = mysqli_stmt_get_result($edit_stmt);
    $edit_scholarship = mysqli_fetch_assoc($edit_result);

    if(!$edit_scholarship) {
        $error = "Scholarship not found.";
    }
}

if(isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $delete_stmt = mysqli_prepare($conn, "DELETE FROM scholarships WHERE id = ?");
    mysqli_stmt_bind_param($delete_stmt, "i", $del_id);
    mysqli_stmt_execute($delete_stmt);
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

    <form method="POST" action="manage_scholarships.php<?php echo $edit_scholarship ? '?edit=' . $edit_scholarship['id'] : ''; ?>" style="background: #f5e7ef; padding: 15px; border-radius: 5px;">
        <h3><?php echo $edit_scholarship ? 'Edit Scholarship' : 'Add New Scholarship'; ?></h3><br>
        <?php if($edit_scholarship): ?>
            <input type="hidden" name="scholarship_id" value="<?php echo $edit_scholarship['id']; ?>">
        <?php endif; ?>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $edit_scholarship ? htmlspecialchars($edit_scholarship['title'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" value="<?php echo $edit_scholarship ? htmlspecialchars($edit_scholarship['description'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Deadline</label>
            <input type="date" name="deadline" value="<?php echo $edit_scholarship ? htmlspecialchars($edit_scholarship['deadline'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
        </div>
        <?php if($edit_scholarship): ?>
            <button type="submit" name="update_scholarship" class="btn">Save Changes</button>
            <a href="manage_scholarships.php" class="btn">Cancel</a>
        <?php else: ?>
            <button type="submit" name="add_scholarship" class="btn">Add Program</button>
        <?php endif; ?>
    </form>
    <br><hr><br>

    <h3>Existing Programs</h3><br>
    <?php if(mysqli_num_rows($result) > 0): ?>
        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <tr style="background-color: #760031; color: white;">
                <th style="padding: 10px;">Title</th>
                <th style="padding: 10px;">Deadline</th>
                <th style="padding: 10px;">Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="padding: 10px;"><?php echo $row['title']; ?></td>
                    <td style="padding: 10px;"><?php echo $row['deadline']; ?></td>
                    <td style="padding: 10px;">
                        <a href="manage_scholarships.php?edit=<?php echo $row['id']; ?>">Edit</a> |
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