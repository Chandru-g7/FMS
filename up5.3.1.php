

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
    $faculty_name = htmlspecialchars($_POST['faculty_name']);
    $award_name = htmlspecialchars($_POST['award_name']);
    $participation_type = htmlspecialchars($_POST['participation_type']);
    $student_name = htmlspecialchars($_POST['student_name']);
    $competition_level = htmlspecialchars($_POST['competition_level']);
    $event_name = htmlspecialchars($_POST['event_name']);
    $month_year = htmlspecialchars($_POST['month_year']);
    $uploaded_at = date('Y-m-d H:i:s');
    $filename = $_POST['file_name'];
    $targetDir = "uploads/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    foreach ($_FILES['files']['name'] as $key => $file_name) {
        $fileTmpPath = $_FILES['files']['tmp_name'][$key];
        $filepath = $targetDir . basename($file_name);

        if (move_uploaded_file($fileTmpPath, $filepath)) {
            $sql = "INSERT INTO files5_3_1 (username, faculty_name, academic_year, award_name, participation_type, student_name, competition_level, event_name, month_year, uploaded_at, file_name, file_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $username, $faculty_name, $academic_year, $award_name, $participation_type, $student_name, $competition_level, $event_name, $month_year, $uploaded_at, $filename, $filepath);

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
    <title>Upload Student Achievements</title>
    <link rel="stylesheet" href="css/upload_aaaa.css">
</head>
<body>
    <div class="upload-container">
        <h1>Upload Student Achievements</h1>
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="academic_year" value="<?php echo htmlspecialchars($academic_year); ?>">
            <input type="hidden" name="criteria" value="<?php echo htmlspecialchars($criteria); ?>">
            <input type="hidden" name="criteria_no" value="<?php echo htmlspecialchars($criteria_no); ?>">

            <label for="faculty_name">Faculty Name:</label>
            <input type="text" id="faculty_name" name="faculty_name" required>
            
            <label for="award_name">Name of the Award/Medal:</label>
            <input type="text" id="award_name" name="award_name" required>

            <label>Team / Individual:</label>
            <div class="radio-group">
                <input type="radio" id="team" name="participation_type" value="Team" required>
                <label for="team">Team</label>
                <input type="radio" id="individual" name="participation_type" value="Individual" required>
                <label for="individual">Individual</label>
            </div>

            <label for="student_name">Name of the Student:</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="competition_level">Level of Competition:</label>
            <select id="competition_level" name="competition_level" required>
                <option value="">Select</option>
                <option value="Inter-university">Inter-university</option>
                <option value="State">State</option>
                <option value="National">National</option>
                <option value="International">International</option>
            </select>

            <label for="event_name">Name of the Event:</label>
            <input type="text" id="event_name" name="event_name" required>

            <label for="month_year">Month and Year:</label>
            <input type="month" id="month_year" name="month_year" required>

            <label for="file_name">File Name:</label>
            <input type="text" id="file_name" name="file_name" required>

            <label for="files">Choose File:</label>
            <input type="file" id="files" name="files[]" required>

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