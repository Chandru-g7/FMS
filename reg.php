<?php
    include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./stuff/gmr_landing_page.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            margin-top: 0px;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height:159vh;
            background: rgba(0, 0, 0, 0.6); /* Adjust the opacity as needed */
            z-index: -1;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .registration-form {
            margin-top: 100px;
            background: rgba(0, 0, 0, 0.7);
            padding: 25px;
            width: 700px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            animation: fadeIn 1.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .registration-form h2 {
            text-align: center;
            color:rgb(93, 162, 215);
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .registration-form label {
            font-weight: bold;
            color: white;
        }

        .registration-form input,
        .registration-form select {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }
        input[type="file"] {
            background-color: white;
            color: black;
        }

        .registration-form input:focus,
        .registration-form select:focus {
            border-color: #8e44ad;
            box-shadow: 0px 0px 5px rgba(142, 68, 173, 0.3);
        }

        .registration-form button {
            margin-left:150px;
            width: 50%;
            padding: 12px;
            background: linear-gradient(135deg,green,yellow);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 15px rgba(255, 150, 150, 0.2);
        }
       
        .registration-form button:hover {
            background: linear-gradient(135deg, #fad0c4, #ff9a9e);
            box-shadow: 0px 4px 20px rgba(255, 150, 150, 0.4);
            transform: translateY(-2px);
        }


        
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #6a82fb, #fc5c7d);
            color: white;
            font-size: 1.1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .home-button:hover {
            background: linear-gradient(135deg, #fc5c7d, #6a82fb);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.4);
            transform: translateY(-3px);
        }
        </style>
    </style>
</head>
<?php
    include 'header.php';
?>
<body>
<div class="registration-form">
    <h2>Employee Registration</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="emp_name">Employee Name:</label>
        <input type="text" name="emp_name" required value="<?php echo isset($_POST['emp_name']) ? htmlspecialchars($_POST['emp_name']) : ''; ?>">

        <label for="emp_no">Employee Number:</label>
        <input type="text" name="emp_no" required value="<?php echo isset($_POST['emp_no']) ? htmlspecialchars($_POST['emp_no']) : ''; ?>">

        <label for="dept">Department:</label>
        <select name="dept" required>
            <option value="">Select Department</option>
            <option value="AIDS" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'AIDS') echo 'selected'; ?>>AIDS</option>
            <option value="AIML" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'AIML') echo 'selected'; ?>>AIML</option>
            <option value="CSE" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'CSE') echo 'selected'; ?>>CSE</option>
            <option value="CIVIL" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'CIVIL') echo 'selected'; ?>>CIVIL</option>
            <option value="MECH" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'MECH') echo 'selected'; ?>>MECH</option>
            <option value="EEE" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'EEE') echo 'selected'; ?>>EEE</option>
            <option value="ECE" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'ECE') echo 'selected'; ?>>ECE</option>
            <option value="IT" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'IT') echo 'selected'; ?>>IT</option>
            <option value="BSH" <?php if (isset($_POST['dept']) && $_POST['dept'] == 'BSH') echo 'selected'; ?>>BSH</option>
        </select>

        <label for="photo">Photo:</label>
        <input type="file" name="photo" accept="image/*" required>

        <label for="date_of_joining">Date of Joining:</label>
        <input type="date" name="date_of_joining" required value="<?php echo isset($_POST['date_of_joining']) ? htmlspecialchars($_POST['date_of_joining']) : ''; ?>">

        <label for="aadhar">Aadhar Card Number:</label>
        <input type="text" name="aadhar" pattern="\d{12}" required title="Aadhar should be 12 digits" value="<?php echo isset($_POST['aadhar']) ? htmlspecialchars($_POST['aadhar']) : ''; ?>">

        <label for="pan">PAN Card Number:</label>
        <input type="text" name="pan" pattern="[A-Z]{5}[0-9]{4}[A-Z]" required title="PAN should be 10 characters" value="<?php echo isset($_POST['pan']) ? htmlspecialchars($_POST['pan']) : ''; ?>">

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" pattern="\d{10}" required title="Phone number should be 10 digits" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">

        <label for="address">Address:</label>
        <input type="text" name="address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">

        <label for="username">Username:</label>
        <input type="text" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="conf_password">Confirm Password:</label>
        <input type="password" name="conf_password" required>

        <button type="submit" name="register">Register</button>
    </form>
</div>

<?php
if (isset($_POST['register'])) {
    $emp_name = $_POST['emp_name'];
    $emp_no = $_POST['emp_no'];
    $dept = $_POST['dept'];
    $date_of_joining = $_POST['date_of_joining'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];

    if ($password != $conf_password) {
        echo "<script>alert('Password and Confirm Password should be equal!');</script>";
    } else {
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle file upload
        $photo = $_FILES['photo'];
        $photo_name = basename($photo['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . "_" . $photo_name;

        if (move_uploaded_file($photo['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO reg_tab (emp_name, emp_no, dept, date_of_joining, aadhar, pan, phone, address, userid, password, photo_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssss", $emp_name, $emp_no, $dept, $date_of_joining, $aadhar, $pan, $phone, $address, $username, $password, $target_file);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!');</script>";
                echo "<script>window.location.href = 'admin/admins.php';</script>";
            } else {
                echo "<p class='error'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Failed to upload photo. Please try again.');</script>";
        }

        $conn->close();
    }
}
?>

</body>
</html>
