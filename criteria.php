<?php
include("connection.php");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQAR Criteria Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(54, 180, 226);
            display: flex;
            justify-content: center;
            margin: 0;
        }
        .container11 {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 80%;
            margin:90px 0px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        #tr2{
            background-color:rgb(3, 2, 71);
            color: white;
        }
        #th1 {
            background-color: #007BFF;
            color: white;
        }
        
        tr:nth-child(even) {
            background-color: #f4f4f4;
        }
        .criteria-no {
            font-weight: bold;
            color: black;
        }
        .btn1 {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn1:hover {
            background-color: #0056b3;
        }
        #cri{
            background-color: #007BFF;
        }
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width:150px;
        }

        .home-button:hover {
            background-color: #c82333;
        }
        .my-uploads-btn {
            position: absolute; /* Allows precise positioning */
            top: 120px; /* Adjusts the vertical position */
            right: 150px; /* Adjusts the horizontal position */
            padding: 10px 20px;
            background-color: #0a640a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 200px;
        }

        .my-uploads-btn:hover {
            background-color: #065821;
        }


    </style>
</head>
<?php
    include './header.php';
?>
<body>
    <div class="container11">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $designation = isset($_GET['designation']) ? htmlspecialchars($_GET['designation']) : 'Unknown';
                $academicYear = isset($_GET['year']) ? htmlspecialchars($_GET['year']) : 'Not Selected';
                $criteria = isset($_GET['criteria']) ? htmlspecialchars($_GET['criteria']) : 'Not Selected';
                echo "<h1>AQAR - " . $academicYear . "</h1>";
            } else {
                echo "<p>No academic year or criteria was selected.</p>";
                exit;
            }
        ?>

        <table>
            <thead>
                <tr>
                    <th colspan="4" id="th1">Criteria <?php echo $criteria; ?></th>
                </tr>
                <tr id="tr2">
                    <th>Criteria No</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($academicYear=='2020-21'){
                        $sql = "SELECT * FROM criteria1 WHERE SI_no='$criteria' order by Sub_no";
                    } else if($academicYear=='2021-22'){
                        $sql = "SELECT * FROM criteria2 WHERE SI_no='$criteria' order by Sub_no";
                    } else {
                        $sql = "SELECT * FROM criteria WHERE SI_no='$criteria' order by Sub_no";
                    }
                    
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $criteriaNo = $row['Sub_no'];  
                            $description = $row['Des'];

                            echo "<tr>";
                            echo "<td class='criteria-no'>$criteriaNo</td>";
                            echo "<td>$description</td>";
                            echo "<td>";
                            echo "<form action='";

                            switch ($criteriaNo) {
                                case '5.1.1':
                                case '5.1.2':
                                    echo "up5.1.1&2.php";
                                    break;
                                case '5.1.3':
                                    echo "up5.1.3.php";
                                    break;
                                case '5.1.4(':
                                    echo "up5.1.4.php";
                                    break;
                                case '5.2.1':
                                    echo "up5.2.1.php";
                                    break;
                                case '5.2.2':
                                    echo "up5.2.2.php";
                                    break;
                                case '5.2.3':
                                    echo "up5.2.3.php";
                                    break;
                                case '5.3.1':
                                    echo "up5.3.1.php";
                                    break;
                                case '5.3.3':
                                    echo "up5.3.3.php";
                                    break;
                                default:
                                    echo "upload.php";
                                    break;
                            }

                            echo "' method='POST'>";
                            echo "<input type='hidden' name='academic_year' value='$academicYear'>";
                            echo "<input type='hidden' name='criteria' value='$criteria'>";
                            echo "<input type='hidden' name='criteria_no' value='$criteriaNo'>";
                            echo "<button type='submit' class='btn1'>upload files</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No data found for the specified criteria.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
        <form action='my_uploads_new.php' method='GET'>
            <input type='hidden' name='a_year' value='<?php echo $academicYear; ?>'>
            <input type='hidden' name='criteria' value='<?php echo $criteria; ?>'>
            <button type='submit' class='my-uploads-btn'>My_uploads</button>
        </form>
    </div>
</body>
</html>
