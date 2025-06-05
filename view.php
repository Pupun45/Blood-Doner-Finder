<?php
$mysqli = new mysqli("localhost", "root", "", "blood_bank");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Bank Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f8f8f8;
        }
        h2 {
            margin-top: 40px;
            color: #333;
        }
        .custom-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-right: 10px;
        }
        .custom-btn:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Blood Bank Data Viewer</h1>

<!-- Filter Buttons -->
<form method="get">
    <button class="custom-btn" name="role" value="">Show All</button>
    <button class="custom-btn" name="role" value="donor">Show Donors</button>
    <button class="custom-btn" name="role" value="receiver">Show Receivers</button>
    <button class="custom-btn" name="help" value="true">Show Help Requests</button>
</form>

<?php
// If viewing help requests
if (isset($_GET['help'])) {
    $sql = "SELECT * FROM help";
    $result = $mysqli->query($sql);

    echo "<h2>Help Requests</h2>";

    if ($result->num_rows > 0) {
        echo "<table>
                <tr><th>ID</th><th>Name</th><th>Contact</th><th>Message</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['contact']) . "</td>
                    <td>" . htmlspecialchars($row['message']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No help requests found.</p>";
    }

    $mysqli->close();
    exit;
}

// Else: Show donors/receivers
$filter = isset($_GET['role']) ? $_GET['role'] : '';
$sql = "SELECT * FROM people";
if ($filter) {
    $sql .= " WHERE role = '" . $mysqli->real_escape_string($filter) . "'";
}
$result = $mysqli->query($sql);

echo "<h2>People (" . ($filter ? ucfirst($filter) : "All") . ")</h2>";

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Role</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Blood Group</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['role']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['contact']) . "</td>
                <td>" . htmlspecialchars($row['dob']) . "</td>
                <td>" . htmlspecialchars($row['gender']) . "</td>
                <td>" . htmlspecialchars($row['blood_group']) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No records found.</p>";
}

$mysqli->close();
?>

</body>
</html>
