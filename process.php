<?php
$mysqli = new mysqli("localhost", "root", "", "blood_bank");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle help form submission
if (isset($_POST['help_submit'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $message = $_POST['message'];

    $stmt = $mysqli->prepare("INSERT INTO help (name, contact, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $contact, $message);

    if ($stmt->execute()) {
        echo "Help request submitted successfully.<br>";
        echo "<a href='view.php?help=true'>View Help Requests</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
    exit;
}

// Handle donor/receiver form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for all required donor/receiver fields
    if (
        isset($_POST['role']) && isset($_POST['name']) && isset($_POST['email']) &&
        isset($_POST['contact']) && isset($_POST['dob']) &&
        isset($_POST['gender']) && isset($_POST['blood_group'])
    ) {
        $role = $_POST['role']; // 'donor' or 'receiver'
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $blood_group = $_POST['blood_group'];

        $stmt = $mysqli->prepare("INSERT INTO people (role, name, email, contact, dob, gender, blood_group) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $role, $name, $email, $contact, $dob, $gender, $blood_group);

        if ($stmt->execute()) {
            echo "Data submitted successfully.<br>";
            echo "<a href='view.php'>View All Records</a>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Some required donor/receiver fields are missing.";
    }

    $mysqli->close();
}
?>
