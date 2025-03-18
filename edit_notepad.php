<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$notepad_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);

    $update_query = $pdo->prepare("UPDATE notepads SET title = ?, content = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
    $result = $update_query->execute([$title, $content, $notepad_id, $user_id]);

    if ($result) {
        header("Location: my_notepads.php");
        exit();
    } else {
        $error = "Error updating notepad.";
    }
} else {
    $notepad_query = $pdo->prepare("SELECT * FROM notepads WHERE id = ? AND user_id = ?");
    $notepad_query->execute([$notepad_id, $user_id]);
    $notepad = $notepad_query->fetch(PDO::FETCH_ASSOC);

    if (!$notepad) {
        header("Location: my_notepads.php");
        exit();
    }
}

include 'common/header.php';
?>


<div class="contAddTask">
    <h2>Edit Notepad</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($notepad['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="10"><?php echo htmlspecialchars($notepad['content']); ?></textarea>
        </div>
        <button type="submit" class="btn">Update Notepad</button>
    </form>
    <a href="my_notepads.php" class="btn">Back to My Notepads</a>
</div>

<?php include 'common/footer.php'; ?>