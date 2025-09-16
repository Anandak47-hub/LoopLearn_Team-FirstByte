<?php 
include("backend/database.php");
$topics=$conn->query("SELECT id,name FROM topics ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Select Topic</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px; }
        h1 { color:#333; }
        select, button {
            padding:10px; font-size:16px; margin-top:10px;
        }
        button {
            background:#007BFF; color:#fff; border:none; cursor:pointer;
            border-radius:5px;
        }
        button:hover { background:#0056b3; }
    </style>
</head>
<body>
    <h1>Choose a Topic</h1>

    <!-- Simple form with a dropdown -->
    <form id="topicForm">
        <label>Topic:</label>
        <select id="topicSelect" name="topic_id" required>
            <option value="">-- Select Topic --</option>
            <?php while($row = $topics->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>
        <button type="button" id="goBtn">Select Topic</button>
    </form>

<script>
document.getElementById('goBtn').addEventListener('click', function () {
    const select = document.getElementById('topicSelect');
    const topicId = select.value;
    const topicName = select.options[select.selectedIndex].text;

    if (!topicId) {
        alert("Please select a topic first.");
        return;
    }

    // Go to the quiz page with topic info
    window.location.href =
        "quiz.html?topic_id=" + topicId +
        "&topic_name=" + encodeURIComponent(topicName);
});
</script>
</body>
</html>