<?php
require_once 'auth.php';
requireAuth();
require_once __DIR__ . '/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$user = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'birthdate' => '',
    'skin_type' => '',
    'skin_concerns' => [],
    'membership' => 'Standard',
    'image' => ''
];

if ($stmt = $conn->prepare('SELECT full_name, email, phone, birthdate, skin_type, skin_concerns, membership_level, profile_image FROM users WHERE id = ? LIMIT 1')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($fullName, $email, $phone, $birthdate, $skinType, $skinConcerns, $membershipLevel, $profileImage);
    if ($stmt->fetch()) {
        $user['name'] = $fullName ?: '';
        $user['email'] = $email ?: '';
        $user['phone'] = $phone ?: '';
        $user['birthdate'] = $birthdate ?: '';
        $user['skin_type'] = $skinType ?: '';
        $user['skin_concerns'] = $skinConcerns ? json_decode($skinConcerns, true) : [];
        $user['membership'] = $membershipLevel ?: 'Standard';
        $user['image'] = $profileImage ?: '';
    }
    $stmt->close();
}

// Handle profile update
$update_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $skin_type = $_POST['skin_type'] ?? '';
    $skin_concerns = isset($_POST['skin_concerns']) ? json_encode($_POST['skin_concerns']) : '[]';
    
    if ($stmt = $conn->prepare('UPDATE users SET full_name = ?, phone = ?, birthdate = ?, skin_type = ?, skin_concerns = ? WHERE id = ?')) {
        $stmt->bind_param('sssssi', $name, $phone, $birthdate, $skin_type, $skin_concerns, $user_id);
        if ($stmt->execute()) {
            $update_message = 'Profile updated successfully!';
            $_SESSION['user_name'] = $name;
            $user['name'] = $name;
            $user['phone'] = $phone;
            $user['birthdate'] = $birthdate;
            $user['skin_type'] = $skin_type;
            $user['skin_concerns'] = json_decode($skin_concerns, true);
        } else {
            $update_message = 'Error updating profile. Please try again.';
        }
        $stmt->close();
    }
}

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $upload_dir = 'uploads/profiles/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['profile_picture'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $file_name = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                if ($stmt = $conn->prepare('UPDATE users SET profile_image = ? WHERE id = ?')) {
                    $stmt->bind_param('si', $file_path, $user_id);
                    if ($stmt->execute()) {
                        $user['image'] = $file_path;
                        $update_message = 'Profile picture updated successfully!';
                    }
                    $stmt->close();
                }
            }
        } else {
            $update_message = 'Invalid file type. Please upload JPG, PNG, or GIF.';
        }
    }
}

$skin_types = ['Normal', 'Dry', 'Oily', 'Combination', 'Sensitive'];
$skin_concerns_list = ['Acne', 'Aging', 'Dark Spots', 'Dryness', 'Redness', 'Sensitivity', 'Oiliness', 'Pores', 'Dullness'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Profile</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --red-side: #C84B31;
      --bg-light: #FFF8F5;
      --text-dark: #2D2424;
      --text-light: #6C6060;
      --border-color: #E8D5CF;
      --input-border: #D4C4BE;
      --input-bg: #FFFFFF;
      --input-focus: #C84B31;
      --btn-bg: #C84B31;
      --btn-hover: #A63E28;
      --shadow: 0 2px 8px rgba(200, 75, 49, 0.08);
      --shadow-hover: 0 4px 16px rgba(200, 75, 49, 0.15);
      --radius: 12px;
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg-light);
      color: var(--text-dark);
      line-height: 1.6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Header Styles */
    .header {
      background: white;
      padding: 1.5rem 0;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--red-side);
      letter-spacing: 2px;
    }

    .nav {
      display: flex;
      align-items: center;
      gap: 2rem;
    }

    .nav a {
      color: var(--text-dark);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
      position: relative;
    }

    .nav a::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--red-side);
      transition: var(--transition);
    }

    .nav a:hover::after {
      width: 100%;
    }

    .user-menu {
      position: relative;
      cursor: pointer;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 2px solid var(--border-color);
      transition: var(--transition);
    }

    .user-menu:hover .user-avatar {
      border-color: var(--red-side);
    }

    .dropdown {
      position: absolute;
      top: 55px;
      right: 0;
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow-hover);
      min-width: 200px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: var(--transition);
      border: 1px solid var(--border-color);
    }

    .user-menu:hover .dropdown {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown a {
      display: block;
      padding: 12px 20px;
      color: var(--text-dark);
      text-decoration: none;
      transition: var(--transition);
      border-bottom: 1px solid var(--border-color);
    }

    .dropdown a:last-child {
      border-bottom: none;
    }

    .dropdown a:hover {
      background: var(--bg-light);
      color: var(--red-side);
    }

    /* Main Content */
    .main {
      padding: 3rem 0;
      min-height: calc(100vh - 400px);
    }

    .profile-header {
      text-align: center;
      margin-bottom: 3rem;
      padding: 2rem;
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .profile-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: var(--text-dark);
      background: linear-gradient(135deg, var(--red-side), #A63E28);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .profile-header p {
      color: var(--text-light);
      font-size: 1.1rem;
    }

    .message {
      padding: 1rem 1.5rem;
      border-radius: var(--radius);
      margin-bottom: 2rem;
      animation: slideIn 0.5s ease;
    }

    .message.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .profile-content {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .profile-section {
      background: white;
      padding: 2.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      transition: var(--transition);
    }

    .profile-section:hover {
      box-shadow: var(--shadow-hover);
      transform: translateY(-2px);
    }

    .profile-section h2 {
      color: var(--red-side);
      margin-bottom: 2rem;
      font-size: 1.6rem;
      font-weight: 600;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--border-color);
    }

    /* Profile Picture Section */
    .profile-picture-section {
      display: flex;
      align-items: center;
      gap: 3rem;
      flex-wrap: wrap;
    }

    .current-picture {
      position: relative;
    }

    .profile-avatar {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      border: 4px solid var(--border-color);
      object-fit: cover;
      transition: var(--transition);
    }

    .current-picture:hover .profile-avatar {
      border-color: var(--red-side);
      box-shadow: 0 0 0 4px rgba(200, 75, 49, 0.1);
    }

    .upload-form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      flex: 1;
      min-width: 250px;
    }

    .file-input-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .file-input {
      display: none;
    }

    .file-label {
      padding: 1rem 1.5rem;
      background: var(--bg-light);
      border: 2px dashed var(--border-color);
      border-radius: var(--radius);
      text-align: center;
      cursor: pointer;
      transition: var(--transition);
      font-weight: 500;
      color: var(--text-dark);
    }

    .file-label:hover {
      border-color: var(--red-side);
      background: white;
      color: var(--red-side);
    }

    /* Form Styles */
    .profile-form {
      width: 100%;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.95rem;
    }

    .form-group input, 
    .form-group select {
      padding: 12px 16px;
      border: 2px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      transition: var(--transition);
      color: var(--text-dark);
    }

    .form-group input:focus, 
    .form-group select:focus {
      outline: none;
      border-color: var(--input-focus);
      background: white;
      box-shadow: 0 0 0 3px rgba(200, 75, 49, 0.1);
    }

    .form-group input:disabled {
      background: #f5f5f5;
      cursor: not-allowed;
      color: var(--text-light);
    }

    .form-group small {
      color: var(--text-light);
      font-size: 0.85rem;
      font-style: italic;
    }

    .checkbox-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: var(--radius);
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 8px;
      transition: var(--transition);
    }

    .checkbox-label:hover {
      background: white;
    }

    .checkbox-label input {
      display: none;
    }

    .checkmark {
      width: 20px;
      height: 20px;
      border: 2px solid var(--input-border);
      border-radius: 4px;
      position: relative;
      transition: var(--transition);
      flex-shrink: 0;
    }

    .checkbox-label input:checked + .checkmark {
      background: var(--btn-bg);
      border-color: var(--btn-bg);
    }

    .checkbox-label input:checked + .checkmark:after {
      content: '✓';
      position: absolute;
      color: white;
      font-size: 14px;
      font-weight: bold;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    /* Buttons */
    .btn {
      padding: 12px 32px;
      border: none;
      border-radius: var(--radius);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      display: inline-block;
      text-align: center;
      font-family: 'Inter', sans-serif;
    }

    .btn-primary {
      background: var(--btn-bg);
      color: white;
    }

    .btn-primary:hover {
      background: var(--btn-hover);
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .btn-outline {
      background: white;
      color: var(--btn-bg);
      border: 2px solid var(--btn-bg);
    }

    .btn-outline:hover {
      background: var(--btn-bg);
      color: white;
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .btn-danger {
      background: #dc3545;
      color: white;
    }

    .btn-danger:hover {
      background: #c82333;
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(220, 53, 69, 0.3);
    }

    .form-actions {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 2px solid var(--border-color);
    }

    /* Account Settings */
    .account-settings {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .setting-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem;
      background: var(--bg-light);
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
      transition: var(--transition);
      flex-wrap: wrap;
      gap: 1rem;
    }

    .setting-item:hover {
      background: white;
      box-shadow: var(--shadow);
    }

    .setting-info {
      flex: 1;
      min-width: 200px;
    }

    .setting-info h3 {
      color: var(--text-dark);
      margin-bottom: 0.25rem;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .setting-info p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .badge {
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
    }

    .badge.premium {
      background: linear-gradient(135deg, #FFD700, #FFA500);
      color: white;
      box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
    }

    /* Footer */
    .footer {
      background: var(--text-dark);
      color: white;
      padding: 3rem 0 1.5rem;
      margin-top: 4rem;
    }

    .footer-sections {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
      padding-bottom: 2rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-section h3 {
      color: white;
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 1rem;
      letter-spacing: 1px;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section ul li {
      margin-bottom: 0.5rem;
    }

    .footer-section a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .footer-section a:hover {
      color: white;
      padding-left: 5px;
    }

    .newsletter {
      display: flex;
      gap: 0.5rem;
    }

    .newsletter-input {
      flex: 1;
      padding: 10px 15px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: var(--radius);
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-family: 'Inter', sans-serif;
    }

    .newsletter-input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .newsletter-btn {
      padding: 10px 20px;
      background: var(--red-side);
      color: white;
      border: none;
      border-radius: var(--radius);
      cursor: pointer;
      font-weight: 600;
      transition: var(--transition);
    }

    .newsletter-btn:hover {
      background: var(--btn-hover);
    }

    .footer-bottom {
      text-align: center;
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .header .container {
        flex-direction: column;
        gap: 1rem;
      }

      .nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }

      .profile-header h1 {
        font-size: 2rem;
      }

      .profile-section {
        padding: 1.5rem;
      }

      .profile-picture-section {
        flex-direction: column;
        text-align: center;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .form-actions {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }

      .setting-item {
        flex-direction: column;
        align-items: flex-start;
      }

      .footer-sections {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="logo">
        <h1>DERMALUXE</h1>
      </div>
      <nav class="nav">
        <a href="shopall.php">Shop</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="store-locator.php">Stores</a>
        <a href="faqs.php">FAQs</a>
        <div class="user-menu">
          <img src="<?php echo $user['image'] ?: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGMEYwRjAiLz4KPGNpcmNsZSBjeD0iMjAiIGN5PSIxNiIgcj0iNSIgZmlsbD0iI0NFQ0VDRSIvPjxwYXRoIGQ9Ik0yOCAzMGMwLTQuNDE4LTQuNDU4LTgtMTAtOFM4IDI1LjU4MiA4IDMwIiBmaWxsPSIjQ0VDRUNFIi8+Cjwvc3ZnPgo='; ?>" 
               alt="Profile" class="user-avatar">
          <div class="dropdown">
            <a href="dashboard.php">Dashboard</a>
            <a href="skin-analysis.php">Skin Analysis</a>
            <a href="support.php">Support</a>
            <a href="logout.php">Logout</a>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <div class="profile-header">
        <h1>Profile Settings</h1>
        <p>Manage your account information and skincare preferences</p>
      </div>

      <?php if ($update_message): ?>
        <div class="message <?php echo strpos($update_message, 'success') !== false ? 'success' : 'error'; ?>">
          <?php echo htmlspecialchars($update_message); ?>
        </div>
      <?php endif; ?>

      <div class="profile-content">
        <!-- Profile Picture Section -->
        <div class="profile-section">
          <h2>Profile Picture</h2>
          <div class="profile-picture-section">
            <div class="current-picture">
              <img src="<?php echo $user['image'] ?: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDE1MCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxjaXJjbGUgY3g9Ijc1IiBjeT0iNzUiIHI9Ijc1IiBmaWxsPSIjRjBGMEYwIi8+CjxjaXJjbGUgY3g9Ijc1IiBjeT0iNjAiIHI9IjMwIiBmaWxsPSIjQ0VDRUNFIi8+PHBhdGggZD0iTTEwNSAxMTVjMC0xNi41NjctMTMuNDMzLTMwLTMwLTMwcy0zMCAxMy40MzMtMzAgMzAiIGZpbGw9IiNDRUNFQ0UiLz4KPC9zdmc+'; ?>" 
                   alt="Profile Picture" class="profile-avatar">
            </div>
            <form method="POST" enctype="multipart/form-data" class="upload-form">
              <div class="file-input-group">
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="file-input">
                <label for="profile_picture" class="file-label">Choose New Photo</label>
              </div>
              <button type="submit" class="btn btn-outline">Upload Photo</button>
            </form>
          </div>
        </div>

        <!-- Personal Information -->
        <div class="profile-section">
          <h2>Personal Information</h2>
          <form method="POST" class="profile-form">
            <input type="hidden" name="update_profile" value="1">
            
            <div class="form-grid">
              <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
              </div>
              
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                <small>Contact support to change email</small>
              </div>
              
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
              </div>
              
              <div class="form-group">
                <label for="birthdate">Birth Date</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="skin_type">Skin Type</label>
              <select id="skin_type" name="skin_type">
                <option value="">Select your skin type</option>
                <?php foreach ($skin_types as $type): ?>
                  <option value="<?php echo $type; ?>" <?php echo $user['skin_type'] === $type ? 'selected' : ''; ?>>
                    <?php echo $type; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Skin Concerns</label>
              <div class="checkbox-grid">
                <?php foreach ($skin_concerns_list as $concern): ?>
                  <label class="checkbox-label">
                    <input type="checkbox" name="skin_concerns[]" value="<?php echo $concern; ?>"
                           <?php echo in_array($concern, $user['skin_concerns']) ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                    <?php echo $concern; ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              <a href="dashboard.php" class="btn btn-outline">Cancel</a>
            </div>
          </form>
        </div>

        <!-- Account Settings -->
        <div class="profile-section">
          <h2>Account Settings</h2>
          <div class="account-settings">
            <div class="setting-item">
              <div class="setting-info">
                <h3>Membership Level</h3>
                <p><?php echo $user['membership']; ?> Member</p>
              </div>
              <?php if ($user['membership'] === 'Standard'): ?>
                <a href="upgrade-membership.php" class="btn btn-primary">Upgrade</a>
              <?php else: ?>
                <span class="badge premium">Premium</span>
              <?php endif; ?>
            </div>
            
            <div class="setting-item">
              <div class="setting-info">
                <h3>Password</h3>
                <p>Keep your account secure</p>
              </div>
              <button class="btn btn-outline" onclick="changePassword()">Change Password</button>
            </div>
            
            <div class="setting-item">
              <div class="setting-info">
                <h3>Two-Factor Authentication</h3>
                <p>Add an extra layer of security</p>
              </div>
              <button class="btn btn-outline">Enable 2FA</button>
            </div>
            
            <div class="setting-item">
              <div class="setting-info">
                <h3>Account Deletion</h3>
                <p>Permanently delete your account</p>
              </div>
              <button class="btn btn-danger" onclick="deleteAccount()">Delete Account</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-sections">
        <div class="footer-section">
          <h3>RESOURCES</h3>
          <ul>
            <li><a href="size-guide.php">Size Guide</a></li>
            <li><a href="ordering-process.php">Ordering Process</a></li>
            <li><a href="care-guide.php">Care Guide</a></li>
            <li><a href="search.php">Search</a></li>
            <li><a href="store-locator.php">Store Locator</a></li>
          </ul>
        </div>

        <div class="footer-section">
          <h3>ABOUT US</h3>
          <ul>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
          </ul>
        </div>

        <div class="footer-section">
          <h3>LEGAL</h3>
          <ul>
            <li><a href="privacy.php">Privacy Policy</a></li>
            <li><a href="terms.php">Terms and conditions</a></li>
            <li><a href="returns.php">Return & Exchange Policy</a></li>
          </ul>
        </div>

        <div class="footer-section">
          <h3>BE THE FIRST TO KNOW</h3>
          <div class="newsletter">
            <input type="email" placeholder="Email address" class="newsletter-input">
            <button class="newsletter-btn">Subscribe</button>
          </div>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; 2025 Dermaluxe. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    function changePassword() {
      const newPassword = prompt('Enter new password:');
      if (newPassword) {
        if (newPassword.length < 8) {
          alert('Password must be at least 8 characters long.');
          return;
        }
        alert('Password change functionality would be implemented here. In production, this would send a secure request to update your password.');
      }
    }

    function deleteAccount() {
      if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        const confirmation = prompt('Type "DELETE" to confirm account deletion:');
        if (confirmation === 'DELETE') {
          alert('Account deletion functionality would be implemented here. In production, this would permanently delete your account and all associated data.');
        } else {
          alert('Account deletion cancelled.');
        }
      }
    }

    // Profile picture preview
    document.getElementById('profile_picture').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
          alert('File size must be less than 5MB');
          this.value = '';
          return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
          alert('Please upload a valid image file (JPG, PNG, or GIF)');
          this.value = '';
          return;
        }

        // Preview the image
        const reader = new FileReader();
        reader.onload = function(e) {
          const avatars = document.querySelectorAll('.profile-avatar');
          avatars.forEach(avatar => {
            avatar.src = e.target.result;
          });
        }
        reader.readAsDataURL(file);
      }
    });

    // Auto-hide success messages after 5 seconds
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
      setTimeout(() => {
        message.style.opacity = '0';
        message.style.transform = 'translateY(-20px)';
        setTimeout(() => {
          message.remove();
        }, 300);
      }, 5000);
    });

    // Form validation
    document.querySelector('.profile-form').addEventListener('submit', function(e) {
      const phone = document.getElementById('phone').value;
      if (phone && !/^\+?[\d\s-()]+$/.test(phone)) {
        e.preventDefault();
        alert('Please enter a valid phone number');
        return;
      }

      const birthdate = document.getElementById('birthdate').value;
      if (birthdate) {
        const date = new Date(birthdate);
        const today = new Date();
        const age = today.getFullYear() - date.getFullYear();
        if (age < 13) {
          e.preventDefault();
          alert('You must be at least 13 years old to use this service');
          return;
        }
      }
    });

    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>
</body>
</html>