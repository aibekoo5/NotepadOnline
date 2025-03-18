<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $status = sanitizeInput($_POST['status']);
    $due_date = sanitizeInput($_POST['due_date']);
    $user_id = $_SESSION['user_id'];

    
    $query = $pdo->prepare("INSERT INTO tasks (user_id, title, description, status, due_date) VALUES (?, ?, ?, ?, ?)");
  
    $result = $query->execute([
        $user_id,
        $title,
        $description,
        $status,
        $due_date
    ]);

    if ($result) {
        header("Location: my_notepads.php");
        exit;
    } else {
        $error = "Failed to add task. Please try again.";
    }
}

include 'common/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task - Task Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="contAddTask">
        <h2>Add New Task</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="add_task.php" method="post" class="taskFrom">
            <input type="text" name="title" placeholder="Task Title" required>
            <textarea name="description" placeholder="Task Description"></textarea>
            <select name="status" required>
                <option value="To Do">To Do</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
            <input type="date" name="due_date" required>
            <button type="submit">Add Task</button>
        </form>
        <a href="main.php" class="btn">Back to Home</a>
    </div>

    <?php 
        include 'common/footer.php';
    ?>
</body>
</html>