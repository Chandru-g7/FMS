<?php
session_start();
require_once 'connection.php'; // Ensure this contains the database connection logic

if (empty($_SESSION['username'])) {
    ?>
    <div class="contain11" style="max-width: 100%; height: 100vh;text-align: center; background-image: linear-gradient(to right, #4CAF50, #81C784); margin: 0px auto; padding: 20px; border-radius: 10px;">

    <h2 class='login-message'>Your not Logged in. Please <a href='./admin/admins.php' class='register-link'>Login_in</a>  to edit your profile.</h2>
    <h2>If you're not registered, <a href='./reg.php' class='register-link'>register here</a>.</h2>

    </div>
    <?php
    exit;
}
include "./header.php";
$username = $_SESSION['username'];
$query = "SELECT emp_name, emp_no, dept, date_of_joining, aadhar, pan, phone, address, password, photo_path, userid FROM reg_tab WHERE userid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p>User data not found. Please <a href='./logout.php'>log in again</a>.</p>";
    exit;
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_name = $_POST['emp_name'];
    $emp_no = $_POST['emp_no'];
    $dept = $_POST['dept'];
    $date_of_joining = $_POST['date_of_joining'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $photo_path = $_FILES['photo_path']['name']; // Get the uploaded photo path

    // If a new photo is uploaded, save it to the server
    if ($photo_path) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo_path"]["name"]);
        move_uploaded_file($_FILES["photo_path"]["tmp_name"], $target_file);
    } else {
        // If no photo is uploaded, keep the existing one
        $target_file = $user['photo_path'];
    }

    $update_query = "
        UPDATE reg_tab 
        SET emp_name = ?, emp_no = ?, dept = ?, date_of_joining = ?, aadhar = ?, pan = ?, phone = ?, address = ?, password = ?, photo_path = ?
        WHERE userid = ?
    ";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param(
        "sssssssssss",
        $emp_name,
        $emp_no,
        $dept,
        $date_of_joining,
        $aadhar,
        $pan,
        $phone,
        $address,
        $password,
        $target_file,
        $username
    );
if ($update_stmt->execute()) {
    echo "<script>
            alert('Profile updated successfully!');
            window.location.href = 'edit_profile.php'; // Change 'profile.php' to the page where you want to redirect
          </script>";
} else {
    echo "<script>
            alert('Failed to update profile. Please try again later.');
            window.location.href = 'edit_profile.php'; // Redirect back to the edit profile page
          </script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg,rgb(143, 105, 184), #2575fc);
            color: #333;
            line-height: 1.6;
        }
        .cont11{
            max-width: 800px;
            margin:     0px auto;
            padding: 20px;  
            border-radius: 10px;
        }
        .contain11{
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg,rgb(143, 105, 184), #2575fc);
            color: #333;
            line-height: 1.6;
        }
        .container11 {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 30px;
        }

        .profile-image {
            display: block;
            margin: 0 auto;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #4CAF50;
            margin-bottom: 20px;
        }

        .profile-info {
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #333;
        }

        .profile-form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        input[type="date"], input[type="file"] {
            width: 100%;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button.submit-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.submit-btn:hover {
            background-color: #45a049;
        }

        .login-message {
            text-align: center;
            color: #e74c3c;
            font-size: 1.2rem;
        }

        .register-link {
            color: #3498db;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .success-message {
            text-align: center;
            color: #27ae60;
            font-size: 1.2rem;
        }

        .error-message {
            text-align: center;
            color: #e74c3c;
            font-size: 1.2rem;
        }

        .password-toggle {
            margin-top: -10px;
        }
    </style>
</head>
<body>
    <div class="cont11">

    <
    <div class="container11">
        <h1>Edit Profile</h1>

        <!-- Display the user's photo if available -->
        <?php
        $photo_path = htmlspecialchars($user['photo_path']);
        if ($photo_path && file_exists($photo_path)) {
            echo "<img src='$photo_path' alt='User Photo' class='profile-image'>";
        } else {
            echo "<img src='uploads/default_pic.png' alt='Default Photo' class='profile-image'>";
        }
        ?>
        
        <!-- Display the userid below the photo -->
        <div class="profile-info">
            <p>User ID: <?= htmlspecialchars($user['userid']); ?></p>
        </div>

        <form method="post" class="profile-form" enctype="multipart/form-data">
            <label for="emp_name">Employee Name:</label>
            <input type="text" id="emp_name" name="emp_name" value="<?= htmlspecialchars($user['emp_name']); ?>" required><br>

            <label for="emp_no">Employee Number:</label>
            <input type="text" id="emp_no" name="emp_no" value="<?= htmlspecialchars($user['emp_no']); ?>" required><br>

            <label for="dept">Department:</label>
            <input type="text" id="dept" name="dept" value="<?= htmlspecialchars($user['dept']); ?>" required><br>

            <label for="date_of_joining">Date of Joining:</label>
            <input type="date" id="date_of_joining" name="date_of_joining" value="<?= htmlspecialchars($user['date_of_joining']); ?>" required><br>

            <label for="aadhar">Aadhar:</label>
            <input type="text" id="aadhar" name="aadhar" value="<?= htmlspecialchars($user['aadhar']); ?>" required><br>

            <label for="pan">PAN:</label>
            <input type="text" id="pan" name="pan" value="<?= htmlspecialchars($user['pan']); ?>" required><br>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?= htmlspecialchars($user['address']); ?></textarea><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?= htmlspecialchars($user['password']); ?>" required>
            <label class="password-toggle"><input type="checkbox" onclick="togglePassword()"> Show Password</label><br>

            <label for="photo_path">Upload New Photo:</label>
            <input type="file" id="photo_path" name="photo_path" accept="image/*"><br>

            <button type="submit" class="submit-btn">Update Profile</button>
        </form>
    </div>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
