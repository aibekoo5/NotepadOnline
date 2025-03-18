<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';
redirectIfNotLoggedIn();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);
    $user_id = $_SESSION['user_id'];

    $query = $pdo->prepare("INSERT INTO notepads (user_id, title, content) VALUES (?, ?, ?)");
    $result = $query->execute([$user_id, $title, $content]);

    if ($result) {
        $notepad_id = $pdo->lastInsertId();
        header("Location: my_notepads.php");
        exit();
    } else {
        $error = "An error occurred when creating a notepad: " . $query->errorInfo()[2];
    }
}

include 'common/header.php';
?>

<div class="contAddTask">
    <h1>Create new notepad</h1>
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="title">Name notepad:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="10" cols="50"></textarea>
        </div>
        <button type="submit" class="btn">Create</button>
    </form>
    <a href="main.php" class="btn">Back to Home</a>
</div>

<?php include 'common/footer.php'; ?>