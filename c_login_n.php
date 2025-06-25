<?php
ob_start(); // Start output buffering at the very top
session_start();
include 'header.php';
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signIn'])) {
        $userid = trim($_POST['userid']);
        $password = trim($_POST['password']);
        $designation = trim($_POST['designation']);

        if ($designation == "faculty") {
            if($_SESSION['username']){
                header("Location: c_aqar_files.php?designation=" . urlencode($designation));
            }
            $stmt = $conn->prepare("SELECT * FROM reg_tab WHERE userid = ? AND password = ?");
            $stmt->bind_param("ss", $userid, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $login_stmt = $conn->prepare("INSERT INTO login_pg (userid, password) VALUES (?, ?)");
                $login_stmt->bind_param("ss", $userid, $password);
                if ($login_stmt->execute() === TRUE) {
                    $_SESSION['username'] = $userid;
                    ob_end_clean();
                    header("Location: c_aqar_files.php?designation=" . urlencode($designation));
                    exit();
                }
                $login_stmt->close();
            }
            $stmt->close();
        } else {
            if ($designation == "dept_coordinator" && $userid == "chandu" && $password == "123") {
                $_SESSION['a_username'] = $userid;
                ob_end_clean();
                header("Location: c_aqar_files.php?designation=" . urlencode($designation));
                exit();
            }
            if ($designation == "hod" && $userid == "hod" && $password == "123") {
                $_SESSION['hod'] = $userid;
                ob_end_clean();
                header("Location: c_aqar_files.php?designation=" . urlencode($designation));
                exit();
            }
            if ($designation == "central_coordinator" && $userid == "central" && $password == "123") {
                $_SESSION['c_cord'] = $userid;
             
                ob_end_clean();
                header("Location: c_aqar_files.php?designation=" . urlencode($designation));
                exit();
            }
            if ($designation == "admin" && $userid == "admin" && $password == "123") {
                $_SESSION['admin'] = $userid;
                ob_end_clean();
                header("Location: ./HOD/acd_year_aa.php?designation=" . urlencode($designation));
                exit();
            }
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FMS</title>
    <style>
        body {
            background-image: url('./stuff/gmr_landing_page.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 110vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        
        .container11{
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            width: 400px;
        }
        #loginForm{
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            width: 400px;
            margin-left: 50px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input,select     {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            font-size: 1em;
        }
        select{
            width:80%;
        }

        button {
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
        .register {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container11">
        <div class="login-container">
        <h2>Please select your designation for</h2>
        <h2>LOGIN</h2>
        <select id="designation">
            <option value="" selected disabled>Choose...</option>
            <option value="faculty">Faculty</option>
            <option value="dept_coordinator">Dept Coordinator</option>
            <option value="hod">HOD</option>
            <option value="central_coordinator">Central Coordinator</option>
            <option value="admin">Admin</option>
        </select><br>
        <button class="btnl" onclick="showLogin()">Submit</button>
        
    </div>
    <div id="loginForm" style="display: none;">
            
        <h2 id="welcomeMessage"></h2>
        <h4>Please login</h4>
        <form method="POST">
            <input type="hidden" name="designation" id="designationHidden">
            <input type="text" placeholder="Username" name="userid" required>
            <input type="password" placeholder="Password" name="password" required>
            <button class="btnl" type="submit" name="signIn">Login</button>
        </form>
        <p id="register" class="register" style="display: none;">Don't have an account? <a href="../reg.php">Register here</a>...</p>
    </div>
    </div>
    <script>
        function showLogin() {
            let designation = document.getElementById("designation").value;
            
            if (designation) {
                if (designation === "faculty" && "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>") {
                    window.location.href = "c_aqar_files.php?designation=" + encodeURIComponent(designation);
                    return;
                }
                
                document.getElementById("welcomeMessage").innerText = "Welcome " + designation.replace("_", " ");
                document.getElementById("loginForm").style.display = "block";
                document.getElementById("register").style.display = (designation === "faculty") ? "block" : "none";
                document.getElementById("designationHidden").value = designation;
            }
        }

    </script>
</body>
</html>
