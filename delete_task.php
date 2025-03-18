<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$task_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$delete_query = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
$result = $delete_query->execute([$task_id, $user_id]);

if ($result) {
    header("Location: my_notepads.php");
    exit();
} else {
    echo "Error deleting task.";
}