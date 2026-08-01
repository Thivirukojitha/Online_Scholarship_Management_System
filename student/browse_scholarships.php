<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

$sql = "SELECT * FROM scholarships ORDER BY deadline ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <h2>Browse Scholarships</h2>
    <p>Here are the currently available scholarship programs.</p>
    <br>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <tr style="background-color: #004080; color: white;">
                <th style="padding: 10px;">Scholarship Name</th>
                <th style="padding: 10px;">Description</th>
                <th style="padding: 10px;">Deadline</th>
                <th style="padding: 10px;">Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="padding: 10px;"><?php echo $row['title']; ?></td>
                    <td style="padding: 10px;"><?php echo $row['description']; ?></td>
                    <td style="padding: 10px;"><?php echo $row['deadline']; ?></td>
                    <td style="padding: 10px;">
                        <a href="apply.php?scholarship_id=<?php echo $row['id']; ?>" style="color: #004080; font-weight: bold;">Apply Now</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No scholarships are available at the moment.</p>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>