<?php
require_once '../includes/db_connect.php';
require_once '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("<div class='container'><h2 class='error'>Access Denied!</h2></div>");
}

$success = '';
$error = '';
$student_id = $_SESSION['user_id'];
$scholarship_id = isset($_GET['scholarship_id']) ? intval($_GET['scholarship_id']) : 0;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sch_id = $_POST['scholarship_id'];
    
    $target_dir = "../uploads/documents/";
    $file_name = time() . "_" . basename($_FILES["document"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if($file_type != "pdf" && $file_type != "jpg" && $file_type != "png") {
        $error = "Sorry, only PDF, JPG, and PNG files are allowed.";
    } else {
        if(move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
            $insert_sql = "INSERT INTO applications (student_id, scholarship_id, document_path, status) VALUES ($student_id, $sch_id, '$file_name', 'Pending')";
            
            if(mysqli_query($conn, $insert_sql)) {
                $success = "Application submitted successfully! You can track the status on your dashboard.";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<div class="container">
    <h2>Submit Application</h2>
    <p>Please upload your supporting documents (PDF, JPG, or PNG).</p>
    <br>

    <?php if($error != '') echo "<div class='error'>$error</div>"; ?>
    <?php if($success != '') echo "<div style='color: green; margin-bottom: 10px;'>$success</div>"; ?>

    <form method="POST" action="apply.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="scholarship_id">Scholarship ID (Selected from Browse Page)</label>
            <input type="text" name="scholarship_id" id="scholarship_id" value="<?php echo $scholarship_id; ?>" readonly required>
        </div>
        <div class="form-group">
            <label for="document">Supporting Document</label>
            <input type="file" name="document" id="document" required>
        </div>
        <button type="submit" class="btn">Submit Application</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>