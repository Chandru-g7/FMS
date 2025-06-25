
<?php
include("connection.php");
session_start();

if (!isset($_SESSION['username'])) {
    die("You need to log in to view this page.");
}

if(isset($_POST['academic_year'])){
    $academic_year = $_POST['academic_year'];
    $criteria = $_POST['criteria'] ?? '';
    $criteria_no = $_POST['criteria_no'] ?? '';
}else{
    echo"please select the academic_year";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    date_default_timezone_set('Asia/Kolkata');

    $username = $_SESSION['username'];
    $faculty_name = htmlspecialchars($_POST['faculty_name']);
    $student_name = htmlspecialchars($_POST['student_name']);
    $programme = htmlspecialchars($_POST['programme']);
    $institution = htmlspecialchars($_POST['institution']);
    $admitted_programme = htmlspecialchars($_POST['admitted_programme']);
    $currentDateTime = date('Y-m-d H:i:s');
    $filename = $_POST['file_name'];

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    foreach ($_FILES['files']['name'] as $key => $file_name) {
        $fileTmpPath = $_FILES['files']['tmp_name'][$key];
        $safeFileName = basename($file_name);
        $filepath = $targetDir . $safeFileName;

        if (move_uploaded_file($fileTmpPath, $filepath)) {
            $sql = "INSERT INTO files5_2_2 (username, faculty_name, academic_year, student_name, programme, institution, admitted_programme, uploaded_at, file_name, file_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssss", $username, $faculty_name, $academic_year, $student_name, $programme, $institution, $admitted_programme, $currentDateTime, $filename, $filepath);

            if (!$stmt->execute()) {
                echo "<script>alert('Database Error: " . $stmt->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error uploading file: $safeFileName');</script>";
        }
    }    
    $stmt->close();
    $conn->close();
    echo "<script>alert('File(s) uploaded successfully.');</script>";
}
?>
<?php include './header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Higher Education Enrollment Form</title>
    <link rel="stylesheet" href="css/upload_aaaa.css">
</head>
<body>
    <div class="upload-container">
        <h1>Higher Education Enrollment Form</h1>
        <form action="" method="POST" enctype="multipart/form-data" >

            <input type="hidden" name="academic_year" value="<?php echo $academic_year; ?>">
            <input type="hidden" name="criteria" value="<?php echo $criteria; ?>">
            <input type="hidden" name="criteria_no" value="<?php echo $criteria_no; ?>">



            <label for="faculty_name">Faculty Name:</label>
            <input type="text" id="faculty_name" name="faculty_name" required>

            <label for="student_name">Name of Student Enrolled for Higher Education:</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="programme">Programme Completed:</label>
            <input type="text" id="programme" name="programme" required>

            <label for="institution">Name of Institution Joined:</label>
            <input type="text" id="institution" name="institution" required>

            <label for="admitted_programme">Name of Programme Admitted To:</label>
            <input type="text" id="admitted_programme" name="admitted_programme" required>

            <label for="file_name">File Name:</label>
            <input type="text" id="file_name" name="file_name" required>

            <label for="files">Choose Files:</label>
            <input type="file" id="files" name="files[]" multiple required>

            <button type="submit" name="upload">Upload</button>
        </form>
    </div>

</body>
</html>

