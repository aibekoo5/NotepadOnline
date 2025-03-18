<?php
require_once 'common/db.php';
require_once 'common/functions.php';
require_once 'common/included.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
      $file_tmp = $_FILES['profile_image']['tmp_name'];
      $file_name = $_FILES['profile_image']['name'];
      $file_size = $_FILES['profile_image']['size'];
      $file_type = $_FILES['profile_image']['type'];

      if ($file_size > 10000000) {
          $error = "File is too large. Maximum size is 10MB.";
      } else {
          $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
          if (!in_array($file_type, $allowed_types)) {
              $error = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
          } else {
              $extension = pathinfo($file_name, PATHINFO_EXTENSION);
              $new_filename = uniqid() . '.' . $extension;
              $upload_dir = 'img/profile_images/';
              $upload_path = $upload_dir . $new_filename;

              if (!is_dir($upload_dir)) {
                  mkdir($upload_dir, 0755, true);
              }

              if (move_uploaded_file($file_tmp, $upload_path)) {
                  $query = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                  if ($query->execute([$upload_path, $user_id])) {
                      $success = "Profile image updated successfully.";
                  } else {
                      $error = "Failed to update profile image in the database.";
                  }
              } else {
                  $error = "Failed to upload the image. Please try again.";
              }
          }
      }
  } else {
      $error = "No file was uploaded or an error occurred during upload.";
  }
}

$query = $pdo->prepare("SELECT profile_image FROM users WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

include 'common/header.php';
?>

<div class="container">
    <h1>Upload Profile Image</h1>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div class="profile-image-upload">
        <h2>Current Profile Image</h2>
        <div class="img">
          <img src="<?php echo $user['profile_image'] ? htmlspecialchars($user['profile_image']) : 'images/default-avatar.png'; ?>" alt="Current Profile Image" class="profile-image">
        </div>

        <h2>Upload New Image</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_image">Select an image file (JPG, PNG, or WEBP, max 10MB):</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/png,image/webp" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Upload Image</button>
        </form>
    </div>

    <div class="profile-actions">
        <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
    </div>
</div>

<?php include 'common/footer.php'; ?>