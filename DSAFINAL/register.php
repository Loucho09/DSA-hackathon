<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';

    if ($fullName === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        // Check duplicate email
        $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        if ($check) {
            $check->bind_param('s', $email);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $error = 'Email is already registered.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
                if ($stmt) {
                    $stmt->bind_param('sss', $fullName, $email, $hash);
                    if ($stmt->execute()) {
                        $success = 'Registration successful! Redirecting to login...';
                        echo "<script>
                            setTimeout(() => {
                                window.location.href = 'login.php?registered=1';
                            }, 2000);
                        </script>";
                    } else {
                        $error = 'Registration failed. Please try again.';
                    }
                    $stmt->close();
                } else {
                    $error = 'Server error. Please try again later.';
                }
            }
            $check->close();
        } else {
            $error = 'Server error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Register</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <div class="register-section">
        <div class="register-card">
          <h2>Create Account</h2>
          
          <?php if ($error): ?>
            <div class="error-message">
              <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="success-message">
              <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <form method="post" action="register.php" class="register-form">
            <div class="form-group">
              <label for="full_name">Full Name</label>
              <input 
                type="text" 
                id="full_name" 
                name="full_name" 
                required 
                placeholder="Enter your full name"
                value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
              >
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input 
                type="email" 
                id="email" 
                name="email" 
                required 
                placeholder="Enter your email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
              >
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                placeholder="Create a password"
                minlength="8"
              >
              <div class="password-requirements">
                Password must be at least 8 characters long
              </div>
            </div>

            <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" 
                required 
                placeholder="Confirm your password"
                minlength="8"
              >
            </div>

            <div class="form-options">
              <label class="show-password">
                <input type="checkbox" id="togglePasswords">
                Show Passwords
              </label>
            </div>

            <button type="submit" class="btn btn-primary">CREATE ACCOUNT</button>

            <div class="login-link">
              <p>Already have an account? <a href="login.php">Sign in here</a></p>
            </div>
          </form>
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
            <li><a href="care-guide.php">Care Guide</a></li>
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
    // Password toggle functionality
    const togglePasswords = document.getElementById('togglePasswords');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    togglePasswords.addEventListener('change', function() {
      const type = this.checked ? 'text' : 'password';
      passwordInput.type = type;
      confirmPasswordInput.type = type;
    });

    // Password validation
    function validatePasswords() {
      const password = passwordInput.value;
      const confirmPassword = confirmPasswordInput.value;
      
      if (confirmPassword && password !== confirmPassword) {
        confirmPasswordInput.style.borderColor = 'var(--error-color)';
      } else if (confirmPassword && password === confirmPassword) {
        confirmPasswordInput.style.borderColor = 'var(--btn-bg)';
      } else {
        confirmPasswordInput.style.borderColor = 'var(--input-border)';
      }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    // Form submission loading state
    const registerForm = document.querySelector('.register-form');
    registerForm.addEventListener('submit', function() {
      const btn = this.querySelector('.btn');
      btn.innerHTML = 'Creating account...';
      btn.disabled = true;
    });
  </script>

  <style>
    :root {
      --red-side: #8F1402;
      --btn-bg: #006336;
      --btn-hover: #00804d;
      --input-bg: #f8f9fa;
      --input-border: #dee2e6;
      --input-focus: #8F1402;
      --text-dark: #2d3748;
      --text-light: #6c757d;
      --text-white: #ffffff;
      --error-color: #dc3545;
      --success-color: #28a745;
      --bg-light: #f8f9fa;
      --border-color: #e9ecef;
      --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      --radius: 8px;
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      color: var(--text-dark);
      background: var(--bg-light);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Header */
    .header {
      background: white;
      border-bottom: 1px solid var(--border-color);
      padding: 1rem 0;
    }

    .header .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo h1 {
      color: var(--red-side);
      font-size: 1.8rem;
      font-weight: 700;
      letter-spacing: 1px;
    }

    .nav {
      display: flex;
      gap: 2rem;
    }

    .nav a {
      text-decoration: none;
      color: var(--text-dark);
      font-weight: 500;
      transition: var(--transition);
    }

    .nav a:hover {
      color: var(--red-side);
    }

    /* Main Content */
    .main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 0;
    }

    .register-section {
      width: 100%;
      max-width: 450px;
    }

    .register-card {
      background: white;
      padding: 2.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .register-card h2 {
      text-align: center;
      margin-bottom: 2rem;
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--text-dark);
    }

    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--input-focus);
      background: white;
      box-shadow: 0 0 0 3px rgba(143, 20, 2, 0.1);
    }

    .password-requirements {
      font-size: 0.8rem;
      color: var(--text-light);
      margin-top: 0.5rem;
    }

    .form-options {
      margin-bottom: 1.5rem;
    }

    .show-password {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: var(--text-light);
      cursor: pointer;
    }

    .show-password input {
      width: auto;
      margin: 0;
    }

    .btn {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: var(--radius);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    .btn-primary {
      background: var(--btn-bg);
      color: white;
    }

    .btn-primary:hover {
      background: var(--btn-hover);
      transform: translateY(-2px);
    }

    .btn:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    .login-link {
      text-align: center;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border-color);
    }

    .login-link a {
      color: var(--red-side);
      text-decoration: none;
      font-weight: 500;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .error-message {
      background: rgba(220, 53, 69, 0.1);
      color: var(--error-color);
      padding: 12px 16px;
      border-radius: var(--radius);
      border: 1px solid rgba(220, 53, 69, 0.2);
      margin-bottom: 1.5rem;
      text-align: center;
      font-size: 0.9rem;
    }

    .success-message {
      background: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
      padding: 12px 16px;
      border-radius: var(--radius);
      border: 1px solid rgba(40, 167, 69, 0.2);
      margin-bottom: 1.5rem;
      text-align: center;
      font-size: 0.9rem;
    }

    /* Footer */
    .footer {
      background: white;
      border-top: 1px solid var(--border-color);
      padding: 3rem 0 1rem;
      margin-top: auto;
    }

    .footer-sections {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .footer-section h3 {
      color: var(--text-dark);
      margin-bottom: 1rem;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section li {
      margin-bottom: 0.5rem;
    }

    .footer-section a {
      color: var(--text-light);
      text-decoration: none;
      transition: var(--transition);
    }

    .footer-section a:hover {
      color: var(--red-side);
    }

    .newsletter {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .newsletter-input {
      padding: 10px 12px;
      border: 1px solid var(--input-border);
      border-radius: var(--radius);
      font-size: 0.9rem;
    }

    .newsletter-btn {
      padding: 10px 12px;
      background: var(--btn-bg);
      color: white;
      border: none;
      border-radius: var(--radius);
      cursor: pointer;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .newsletter-btn:hover {
      background: var(--btn-hover);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 2rem;
      border-top: 1px solid var(--border-color);
      color: var(--text-light);
      font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .header .container {
        flex-direction: column;
        gap: 1rem;
      }

      .nav {
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
      }

      .register-card {
        padding: 2rem;
      }

      .footer-sections {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .main {
        padding: 2rem 0;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 0 15px;
      }

      .register-card {
        padding: 1.5rem;
      }

      .nav {
        gap: 0.75rem;
      }

      .nav a {
        font-size: 0.9rem;
      }
    }
  </style>
</body>
</html>