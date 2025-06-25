<?php
// Include database connection
include 'connection.php';
include "./header_hod.php";

// Fetch all records from the contact_form table, sorted by created_at in descending order
$sql = "SELECT full_name, email, phone, subject, message, submitted_at FROM contact_form ORDER BY submitted_at DESC";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <style>
        /* Overall Container */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0px;
            padding: 0;
            background: linear-gradient(to right, #74ebd5, #9face6);
            color: #333;
        }
        .cont11{
            text-align: center;
            margin-left: 30px;
            width: 80%;
            padding: 20px;
        }
        .container11 {
            margin-top:50px;
            width: 80%;
            padding: 20px;
        }

        /* Header */
        h1 {
            margin-left: 300px;
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        /* Table Styling */
        table {
            width: 90vw;
            border-collapse: collapse;
            margin-top: 20px;
        }

        /* Table Header */
        th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            text-align: left;
        }

        /* Table Rows */
        td {
            background-color: #f9f9f9;
            padding: 12px;
            border: 1px solid #ddd;
        }

        /* Table Hover Effect */
        tr:hover {
            background-color: #f1f1f1;
        }

        /* No Records Found Message */
        td[colspan="6"] {
            text-align: center;
            font-size: 1.2em;
            color: #999;
        }

        /* Adjust column width for date */
        td:nth-child(6) {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="cont11">
    <div class="container11">
        <h1>All Messages</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Loop through and display each record
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['submitted_at']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No messages found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>
