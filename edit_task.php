<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$task_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $due_date = sanitizeInput($_POST['due_date']);

    $update_query = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ? AND user_id = ?");
    $result = $update_query->execute([$title, $description, $due_date, $task_id, $user_id]);

    if ($result) {
        header("Location: my_notepads.php");
        exit();
    } else {
        $error = "Error updating task.";
    }
} else {
    $task_query = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $task_query->execute([$task_id, $user_id]);
    $task = $task_query->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header("Location: my_notepads.php");
        exit();
    }
}

include 'common/header.php';
?>

<div class="contAddTask">
    <h2>Edit Task</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
        </div>
        <button type="submit" class="btn">Update Task</button>
    </form>
    <a href="my_notepads.php" class="btn">Back to My Notepads</a>
</div>

<?php include 'common/footer.php'; ?>