<?php 
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

$task_query = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC");
$task_query->execute([$user_id]);
$tasks = $task_query->fetchAll(PDO::FETCH_ASSOC);

$notepad_query = $pdo->prepare("SELECT * FROM notepads WHERE user_id = ? ORDER BY updated_at DESC LIMIT 5");
$notepad_query->execute([$user_id]);
$notepads = $notepad_query->fetchAll(PDO::FETCH_ASSOC);

include 'common/header.php';
?>


<div class="container">
    <h2>Your Tasks</h2>
    <?php if ($tasks): ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <input type="checkbox" id="task-<?php echo $task['id']; ?>" onchange="toggleLineThrough(this)">
                    <label for="task-<?php echo $task['id']; ?>" class="tTitle"><?php echo htmlspecialchars($task['title']); ?></label>
                    <span class="date"><?php echo htmlspecialchars($task['due_date']); ?></span>
                    <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn-edit" title="Edit Task">Edit</a>
                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this task?');" title="Delete Task">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>


    <?php else: ?>
        <p>No tasks found.</p>
    <?php endif; ?>

    <h2>Recent Notepads</h2>
    <?php if ($notepads): ?>
        <div class="dropdown">
            <?php foreach ($notepads as $notepad): ?>
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="nTitle"><?php echo htmlspecialchars($notepad['title']); ?></span>
                    <small class="date">Last updated: <?php echo htmlspecialchars($notepad['updated_at']); ?></small>
                </button>
                <a href="edit_notepad.php?id=<?php echo $notepad['id']; ?>" class="btn-edit">Edit</a>
                <a href="delete_notepad.php?id=<?php echo $notepad['id']; ?>" class="btn-delete">Delete</a>
                <textarea class="dropdown-menu">
                    <?php echo htmlspecialchars($notepad['content']); ?>
                </textarea>
                <br>
                <hr>
            <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No recent notepads found.</p>
<?php endif; ?>
</div>

<?php include 'common/footer.php'; ?>