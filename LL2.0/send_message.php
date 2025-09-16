
<?php
session_start();
include("database.php");

// show all PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ✅ check that session email exists
    if (!isset($_SESSION['email'])) {
        http_response_code(400);
        echo "ERROR: session email is not set.";
        exit;
    }

    $sender  = $_SESSION['email'];
    $message = trim($_POST['message'] ?? '');

    if ($message === '') {
        http_response_code(400);
        echo "ERROR: message is empty.";
        exit;
    }

    // ✅ prepared statement with error reporting
    $stmt = $conn->prepare("INSERT INTO messages (sender, message) VALUES (?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ss", $sender, $message);
    if (!$stmt->execute()) {
        http_response_code(500);
        echo "Execute failed: " . $stmt->error;
        exit;
    }

    echo "OK"; // success indicator
}
?>