<?php 
include("database.php");

//
if(isset($_POST['new_topic'])&& !empty($_POST['new_topic'])){
    $newTopic= trim($_POST['new_topic']);
    $sql="INSERT INTO topics(name) VALUES(?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param('s',$newTopic);
    $stmt->execute();
    echo "<p style='color:green;'>New topic '$newTopic'added!</p>";
}

if(isset($_POST['submit_question'])){
    $topic_id=intval($_POST['topic_id']);
    $question_text= trim($_POST['question_text']);
    $option_a= trim($_POST['option_a']);
    $option_b= trim($_POST['option_b']);
    $option_c= trim($_POST['option_c']);
    $option_d= trim($_POST['option_d']);
    $correct_option= $_POST['correct_option'];
    $difficulty =$_POST['difficulty'];

    $stmt=$conn->prepare("INSERT INTO questions(topic_id,question_text,option_a,option_b,option_c,option_d,correct_option, difficulty) VALUES(?,?,?,?,?,?,?,?)");
    $stmt->bind_param('isssssss',
                        $topic_id,$question_text,$option_a,$option_b,$option_c,$option_d,$correct_option,$difficulty);
    $stmt->execute();
    echo "<p style='color:green;'>Question added successfully!</p>";
}
$topics=$conn->query("SELECT id,name FROM topics ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Question</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        h1 { color: #333; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin-bottom: 40px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type=text], textarea, select {
            width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box;
        }
        input[type=submit] { margin-top: 15px; padding: 10px 20px; background: #007BFF; border: none; color: #fff; cursor: pointer; }
        input[type=submit]:hover { background: #0056b3; }
        .section { margin-bottom: 30px; }
    </style>
</head>
<body>

<h1>Add a New Question</h1>

<!-- Section to add a brand new topic -->
<div class="section">
    <h2>Add New Topic</h2>
    <form method="POST">
        <label>Topic Name:</label>
        <input type="text" name="new_topic" required>
        <input type="submit" value="Add Topic">
    </form>
</div>

<!-- Section to add a question -->
<div class="section">
    <h2>Add Question to Existing Topic</h2>
    <form method="POST">
        <label>Topic:</label>
        <select name="topic_id" required>
            <option value="">-- Select Topic --</option>
            <?php while($row = $topics->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Question Text:</label>
        <textarea name="question_text" rows="3" required></textarea>

        <label>Option A:</label>
        <input type="text" name="option_a" required>

        <label>Option B:</label>
        <input type="text" name="option_b" required>

        <label>Option C:</label>
        <input type="text" name="option_c" required>

        <label>Option D:</label>
        <input type="text" name="option_d" required>

        <label>Correct Option:</label>
        <select name="correct_option" required>
            <option value="a">A</option>
            <option value="b">B</option>
            <option value="c">C</option>
            <option value="d">D</option>
        </select>

        <label>Difficulty:</label>
        <select name="difficulty" required>
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
        </select>

        <input type="submit" name="submit_question" value="Add Question">
    </form>
</div>

</body>
</html>