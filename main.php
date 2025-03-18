<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

include 'common/header.php'
?>

<div class="container">
    <h2>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h2>
    <a href="add_task.php" class="btn">Add New Task</a>
    <a href="create_notepad.php" class="btn">Create new notepad</a>
</div>

<?php include 'common/footer.php'?>


