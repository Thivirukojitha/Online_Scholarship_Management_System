<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

if(isset($_GET['action']) && isset($_GET['id'])) {
    $app_id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if($action == 'approve') {
        mysqli_query($conn, "UPDATE applications SET status = 'Approved' WHERE id = $app_id");
    } elseif($action == 'reject') {
        mysqli_query($conn, "UPDATE applications SET status = 'Rejected' WHERE id = $app_id");
    }
    header("Location: manage_applications.php");
    exit();
}

$query = "SELECT applications.id, users.username, scholarships.title, applications.document_path, applications.status, applications.applied_date FROM applications JOIN users ON applications.student_id = users.id JOIN scholarships ON applications.scholarship_id = scholarships.id ORDER BY applications.applied_date DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container">
    <h2>Review Applications</h2>
    <p>Review submitted documents and update the status.</p>
    <br>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <tr style="background-color: #760031; color: white;">
                <th style="padding: 10px;">Student</th>
                <th style="padding: 10px;">Scholarship</th>
                <th style="padding: 10px;">Document</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px;">Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="padding: 10px;"><?php echo $row['username']; ?></td>
                    <td style="padding: 10px;"><?php echo $row['title']; ?></td>
                    <td style="padding: 10px;">
                        <a href="../uploads/documents/<?php echo $row['document_path']; ?>" target="_blank">View File</a>
                    </td>
                    <td style="padding: 10px;"><b><?php echo $row['status']; ?></b></td>
                    <td style="padding: 10px;">
                        <?php if($row['status'] == 'Pending'): ?>
                            <a href="manage_applications.php?action=approve&id=<?php echo $row['id']; ?>" style="color: green;">Approve</a> | 
                            <a href="manage_applications.php?action=reject&id=<?php echo $row['id']; ?>" style="color: red;">Reject</a>
                        <?php else: ?>
                            Evaluated
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No applications found.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>