<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2><p>You must be an Administrator to view this page.</p></div>");
}

$success = '';
$error = '';
$edit_user = null;

if(isset($_GET['edit_user'])) {
    $edit_user_id = intval($_GET['edit_user']);
    $edit_stmt = mysqli_prepare($conn, "SELECT id, username, full_name, age FROM users WHERE id = ? AND role = 'student'");

    if($edit_stmt) {
        mysqli_stmt_bind_param($edit_stmt, "i", $edit_user_id);
        mysqli_stmt_execute($edit_stmt);
        $edit_result = mysqli_stmt_get_result($edit_stmt);
        $edit_user = mysqli_fetch_assoc($edit_result);
        mysqli_stmt_close($edit_stmt);
    }

    if(!$edit_user) {
        $error = 'The selected student account could not be found.';
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $username = trim($_POST['username'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $age = trim($_POST['age'] ?? '');

    $edit_user = [
        'id' => $user_id,
        'username' => $username,
        'full_name' => $full_name,
        'age' => $age
    ];

    if($username === '' || $full_name === '') {
        $error = 'Username and full name are required.';
    } elseif(!ctype_digit($age) || intval($age) <= 0) {
        $error = 'Please enter a valid age.';
    } else {
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? AND id != ?");
        $update_stmt = mysqli_prepare($conn, "UPDATE users SET username = ?, full_name = ?, age = ? WHERE id = ? AND role = 'student'");

        if($check_stmt && $update_stmt) {
            mysqli_stmt_bind_param($check_stmt, "si", $username, $user_id);
            mysqli_stmt_execute($check_stmt);
            $username_exists = mysqli_stmt_get_result($check_stmt);

            if(mysqli_num_rows($username_exists) > 0) {
                $error = 'Username already exists. Please choose another.';
            } else {
                $age_value = intval($age);
                mysqli_stmt_bind_param($update_stmt, "ssii", $username, $full_name, $age_value, $user_id);

                if(mysqli_stmt_execute($update_stmt) && mysqli_stmt_affected_rows($update_stmt) === 1) {
                    $success = 'User details updated successfully.';
                    $edit_user = null;
                } else {
                    $error = 'The user details could not be updated.';
                }
            }
        } else {
            $error = 'The user details could not be updated.';
        }

        if($check_stmt) {
            mysqli_stmt_close($check_stmt);
        }
        if($update_stmt) {
            mysqli_stmt_close($update_stmt);
        }
    }
}

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
    <p>View registered accounts and manage student users when necessary.</p>
    <br>

    <?php if($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if($success !== ''): ?>
        <div style="color: green; margin-bottom: 10px;"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if($edit_user): ?>
        <h3>Edit User</h3>
        <form method="POST" action="manage_users.php">
            <input type="hidden" name="user_id" value="<?php echo (int) $edit_user['id']; ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" maxlength="50" value="<?php echo htmlspecialchars($edit_user['username'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" maxlength="255" value="<?php echo htmlspecialchars($edit_user['full_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" name="age" id="age" min="1" value="<?php echo (int) $edit_user['age']; ?>" required>
            </div>
            <button type="submit" name="update_user" class="btn">Save Changes</button>
            <a href="manage_users.php">Cancel</a>
        </form>
        <br>
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
                            <a href="manage_users.php?edit_user=<?php echo (int) $row['id']; ?>" class="btn action-btn">Edit</a>
                            <form method="POST" action="manage_users.php" class="action-form" onsubmit="return confirm('Delete this user and all of their applications?');">
                                <input type="hidden" name="user_id" value="<?php echo (int) $row['id']; ?>">
                                <button type="submit" name="delete_user" class="btn action-btn">Delete</button>
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