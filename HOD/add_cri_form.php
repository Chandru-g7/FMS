<?php
include("connection.php");
session_start();

// Enable exception mode for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Get the academic year and criteria from the POST request
    $academic_year = isset($_POST['academic_year']) ? $_POST['academic_year'] : '';
    $criteria = isset($_POST['criteria']) ? $_POST['criteria'] : '';

    // Check if the upload form was submitted
    if (isset($_POST['upload'])) {
        // Retrieve form data
        $criteria_no = $_POST['cri_no'];
        $description = $_POST['des'];

        // Determine the correct table based on academic year
        if ($academic_year == '2020-21') {
            $stmt = $conn->prepare("INSERT INTO criteria1 ( SI_no , Sub_no, Des, year) VALUES (?, ?, ?, ?)");
        } else if ($academic_year == '2021-22') {
            $stmt = $conn->prepare("INSERT INTO criteria2 ( SI_no, Sub_no, Des, year) VALUES (?, ?, ?, ?)");
        } else {
            $stmt = $conn->prepare("INSERT INTO criteria ( SI_no, Sub_no, Des, year) VALUES (?, ?, ?, ?)");
        }

        // Bind the parameters
        $stmt->bind_param("ssss", $criteria, $criteria_no, $description, $academic_year);

        // Execute the statement
        $stmt->execute();
        echo "<script>alert('Record Added successfully.');</script>";

        // Close the statement
        $stmt->close();
    }
} catch (mysqli_sql_exception $e) {
    // Handle duplicate entry error
    if ($e->getCode() == 1062) {
        echo "<script>alert('Error: Criteria number already exists and it should be unique.');</script>";
    } else {
        echo "<script>alert('Error Adding the criteria: " . $e->getMessage() . "');</script>";
    }
}

// Close the database connection
$conn->close();
?>


<?php
    include "./header_hod.php";
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <link rel='stylesheet' href="../css/upload_a1.css">
</head>
<body>

    <!-- Logout Button -->
    

    <div class="upload-container">
        <h1>Upload Files</h1>
        <form action="add_cri_form.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <!-- Hidden fields for academic_year, criteria -->
            <input type="hidden" name="academic_year" id="academic_year" value="<?php echo $academic_year; ?>">
            <input type="hidden" name="criteria" id="criteria" value="<?php echo $criteria; ?>">

            <label for="cri_no">Criteria No:</label>
            <input type="text" id="cri_no" name="cri_no" required>

            <label for="des">Description:</label>
            <input type="text" id="des" name="des" required>

            <button type="submit" name="upload" id='but'>Upload</button>
        </form>
    </div>

</body>
<script>
    function validateForm() {
        var academic_year = document.getElementById('academic_year').value;
        var criteria = document.getElementById('criteria').value;

        if (academic_year === '' || criteria === '') {
            alert('Please fill out the academic year, criteria, and criteria number.');
            return false; // Prevent form submission
        }

        return true; // Proceed with form submission if validation passes
    }
</script>
</html>
