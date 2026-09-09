<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2><p>You must be an Administrator to view this page.</p></div>");
}

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);

    if($user_id === intval($_SESSION['user_id'])) {
        $error = 'You cannot delete the account you are currently using.';
    } else {
        mysqli_begin_transaction($conn);

        $delete_applications_stmt = mysqli_prepare($conn, "DELETE FROM applications WHERE student_id = ?");
        $delete_user_stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'student'");

        if($delete_applications_stmt && $delete_user_stmt) {
            mysqli_stmt_bind_param($delete_applications_stmt, "i", $user_id);
            mysqli_stmt_bind_param($delete_user_stmt, "i", $user_id);

            if(mysqli_stmt_execute($delete_applications_stmt) && mysqli_stmt_execute($delete_user_stmt) && mysqli_stmt_affected_rows($delete_user_stmt) === 1) {
                mysqli_commit($conn);
                $success = 'User deleted successfully.';
            } else {
                mysqli_rollback($conn);
                $error = 'The user could not be deleted.';
            }
        } else {
            mysqli_rollback($conn);
            $error = 'The user could not be deleted.';
        }

        if($delete_applications_stmt) {
            mysqli_stmt_close($delete_applications_stmt);
        }
        if($delete_user_stmt) {
            mysqli_stmt_close($delete_user_stmt);
        }
    }
}

$result = mysqli_query($conn, "SELECT id, username, full_name, age, role FROM users ORDER BY role ASC, username ASC");
?>

<div class="container">
    <h2>Manage Users</h2>
    <p>View registered accounts and remove student users when necessary.</p>
    <br>

    <?php if($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if($success !== ''): ?>
        <div style="color: green; margin-bottom: 10px;"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if($result && mysqli_num_rows($result) > 0): ?>
        <table class="table">
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo (int) $row['age']; ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row['role']), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if($row['role'] === 'student'): ?>
                            <form method="POST" action="manage_users.php" onsubmit="return confirm('Delete this user and all of their applications?');">
                                <input type="hidden" name="user_id" value="<?php echo (int) $row['id']; ?>">
                                <button type="submit" name="delete_user" class="btn" style="background-color: #b00020; margin-top: 0;">Delete</button>
                            </form>
                        <?php else: ?>
                            <span>Protected</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>