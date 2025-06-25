<?php
include("connection.php");
session_start();

if (!isset($_SESSION['username'])) {
    die("You need to log in to view your uploads.");
}

// Get academic year, criteria, and criteria number from POST request
$academic_year = $_POST['academic_year'] ?? '';
$criteria = $_POST['criteria'] ?? '';
$criteria_no = $_POST['criteria_no'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    date_default_timezone_set('Asia/Kolkata');
    $username = $_SESSION['username'];
    $faculty_name = $_POST['faculty_name'];
    $filename = $_POST['file_name'];
    $programme_name = $_POST['programme_name'];
    $year = $_POST['year'];
    $students_enrolled = $_POST['students_enrolled'];
    $agency_details = $_POST['agency_details'];
    $currentDateTime = date('Y-m-d H:i:s');
    $targetDir = "uploads/";
    
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    foreach ($_FILES['files']['name'] as $key => $file_name) {
        $fileTmpPath = $_FILES['files']['tmp_name'][$key];
        $filepath = $targetDir . basename($file_name);

        if (move_uploaded_file($fileTmpPath, $filepath)) {
            $sql = "INSERT INTO files5_1_3 (username, academic_year, faculty_name, programme_name, year, students_enrolled, agency_details, uploaded_at, file_name, file_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssss", 
                              $username, $academic_year, $faculty_name, $programme_name, $year, $students_enrolled, $agency_details, $currentDateTime, $filename, $filepath);

            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "<p class='error-message'>Error moving uploaded file: $file_name</p>";
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
    <title>File Upload</title>
    <link rel="stylesheet" href="css/upload_aaaa.css">
</head>
<body>
    <div class="upload-container">
        <h1>Capacity Development and Skill Enhancement Form</h1>
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="academic_year" value="<?php echo htmlspecialchars($academic_year); ?>">
            <input type="hidden" name="criteria" value="<?php echo htmlspecialchars($criteria); ?>">
            <input type="hidden" name="criteria_no" value="<?php echo htmlspecialchars($criteria_no); ?>">

            <label for="faculty_name">Faculty Name:</label>
            <input type="text" id="faculty_name" name="faculty_name" required>

            <label for="programme_name">Programme Name:</label>
            <input type="text" id="programme_name" name="programme_name" required>

            <label for="year">Year of Implementation:</label>
            <input type="number" id="year" name="year" required>

            <label for="students_enrolled">Students Enrolled:</label>
            <input type="number" id="students_enrolled" name="students_enrolled" required>

            <label for="agency_details">Agency Details (if any):</label>
            <textarea id="agency_details" name="agency_details" rows="4"></textarea>

            <label for="file_name">File Name:</label>
            <input type="text" id="file_name" name="file_name" required>

            <label for="files">Choose Files:</label>
            <input type="file" id="files" name="files[]" multiple required>

            <button type="submit" name="upload">Upload</button>
        </form>
    </div>

    <script>
        function validateForm() {
            const academic_year = document.getElementsByName('academic_year')[0].value;
            const criteria = document.getElementsByName('criteria')[0].value;
            const criteria_no = document.getElementsByName('criteria_no')[0].value;

            if (!academic_year || !criteria || !criteria_no) {
                alert('Please fill out the academic year, criteria, and criteria number.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>