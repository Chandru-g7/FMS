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

date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['upload'])) {
    $username = $_SESSION['username'];
    $faculty_name = $_POST['faculty_name'];
    $filename = $_POST['file_name'];
    
    // Scholarship-related fields
    $scheme_name = $_POST['scheme_name'];
    $gov_students = $_POST['gov_students'];
    $gov_amount = $_POST['gov_amount'];
    $inst_students = $_POST['inst_students'];
    $inst_amount = $_POST['inst_amount'];
    $ngo_students = $_POST['ngo_students'];
    $ngo_amount = $_POST['ngo_amount'];
    $ngo_name = $_POST['ngo_name'];

    $targetDir = "uploads/";
    $currentDateTime = date('Y-m-d H:i:s'); // Get current date and time

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    foreach ($_FILES['files']['name'] as $key => $file_name) {
        $filepath = $targetDir . basename($file_name);

        if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $filepath)) {
            $sql = "INSERT INTO files5_1_1and2 (UserName, academic_year, faculty_name, uploaded_at, file_name, file_path, 
                                       scheme_name, gov_students, gov_amount, inst_students, inst_amount, 
                                       ngo_students, ngo_amount, ngo_name, criteria, criteria_no) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssssssss", 
                              $username, $academic_year, $faculty_name, $currentDateTime, $filename, $filepath, 
                              $scheme_name, $gov_students, $gov_amount, $inst_students, $inst_amount, 
                              $ngo_students, $ngo_amount, $ngo_name, $criteria, $criteria_no);

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
        <h1>Scholarship and Freeship Form</h1>
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="academic_year" value="<?php echo $academic_year; ?>">
            <input type="hidden" name="criteria" value="<?php echo $criteria; ?>">
            <input type="hidden" name="criteria_no" value="<?php echo $criteria_no; ?>">

            <label for="faculty_name">Faculty Name:</label>
            <input type="text" id="faculty_name" name="faculty_name" required>

            <label for="scheme_name">Scheme Name:</label>
            <input type="text" id="scheme_name" name="scheme_name" required>

            <h3>Government Scholarships</h3>
            <label for="gov_students">Number of Students:</label>
            <input type="number" id="gov_students" name="gov_students" required>
            <label for="gov_amount">Amount:</label>
            <input type="number" id="gov_amount" name="gov_amount" required>

            <h3>Institution Scholarships</h3>
            <label for="inst_students">Number of Students:</label>
            <input type="number" id="inst_students" name="inst_students" required>
            <label for="inst_amount">Amount:</label>
            <input type="number" id="inst_amount" name="inst_amount" required>

            <h3>Non-Government Scholarships</h3>
            <label for="ngo_students">Number of Students:</label>
            <input type="number" id="ngo_students" name="ngo_students" required>
            <label for="ngo_amount">Amount:</label>
            <input type="number" id="ngo_amount" name="ngo_amount" required>
            <label for="ngo_name">NGO/Agency Name:</label>
            <input type="text" id="ngo_name" name="ngo_name" required>

            <label for="file_name">File Name:</label>
            <input type="text" id="file_name" name="file_name" required>

            <label for="file">Choose files to upload:</label>
            <input type="file" id="file" name="files[]" multiple required>

            <button type="submit" name="upload">Upload</button>
        </form>
    </div>

    <script>
        function validateForm() {
            var academic_year = document.getElementsByName('academic_year')[0].value;
            var criteria = document.getElementsByName('criteria')[0].value;
            var criteria_no = document.getElementsByName('criteria_no')[0].value;

            if (!academic_year || !criteria || !criteria_no) {
                alert('Please fill out the academic year, criteria, and criteria number.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
