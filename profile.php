<?php 
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$query = $pdo->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $error = "Unable to fetch user data.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_username = sanitizeInput($_POST['username']);
        $new_email = sanitizeInput($_POST['email']);
     
        $email_check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $email_check->execute([$new_email, $user_id]);
        if ($email_check->fetch()) {
            $error = "Email is already in use.";
        } else {
            $update_query = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            if ($update_query->execute([$new_username, $new_email, $user_id])) {
                $success = "Profile updated successfully.";
                $user['username'] = $new_username;
                $user['email'] = $new_email;
            } else {
                $error = "Failed to update profile.";
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password
        $password_check = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $password_check->execute([$user_id]);
        $user_password = $password_check->fetchColumn();

        if (!password_verify($current_password, $user_password)) {
            $error = "Current password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match.";
        } elseif (strlen($new_password) < 8) {
            $error = "New password must be at least 8 characters long.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($update_password->execute([$hashed_password, $user_id])) {
                $success = "Password changed successfully.";
            } else {
                $error = "Failed to update password.";
            }
        }
    }
}

include 'common/header.php';
?>

<div class="container">
    <h1>User Profile</h1>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="profile">
      <div class="img">
        <img src="<?php echo $user['profile_image'] ? htmlspecialchars($user['profile_image']) : 'images/default-avatar.png'; ?>" alt="Profile Image" class="profile-image">
      </div>
         
        <form action="" method="post" class="profile-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="profile-actions">
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                <a href="upload_profile_image.php" class="btn btn-secondary">Upload Profile Image</a>
            </div>
        </form>

        <form action="" method="post" class="change-password-form">
            <h2>Change Password</h2>
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="profile-actions">
                <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
            </div>
        </form>
    </div>
</div>

<?php include 'common/footer.php'; ?>