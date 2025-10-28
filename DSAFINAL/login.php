<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/db.php';

$error = '';
$success = '';

// Check for registration success
if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $success = 'Account created successfully! Please log in.';
}

// Check for logout message
if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    $success = 'You have been logged out successfully.';
}

// Check for session expired
if (isset($_GET['expired']) && $_GET['expired'] === '1') {
    $error = 'Your session has expired. Please log in again.';
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';
    $remember = isset($_POST['remember']) ? true : false;

    if ($email !== '' && $password !== '') {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $stmt = $conn->prepare('SELECT id, password_hash, full_name, email FROM users WHERE email = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($userId, $passwordHash, $fullName, $userEmail);
                    $stmt->fetch();
                    
                    if (password_verify($password, $passwordHash)) {
                        // Regenerate session ID to prevent session fixation
                        session_regenerate_id(true);
                        
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_name'] = $fullName;
                        $_SESSION['user_email'] = $userEmail;
                        $_SESSION['login_time'] = time();
                        
                        // Handle "Remember Me"
                        if ($remember) {
                            $token = bin2hex(random_bytes(32));
                            $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                            
                            setcookie('remember_token', $token, $expiry, '/', '', true, true);
                            
                            // Store token in database (you should create a remember_tokens table)
                            $updateStmt = $conn->prepare('UPDATE users SET remember_token = ?, remember_token_expiry = FROM_UNIXTIME(?) WHERE id = ?');
                            if ($updateStmt) {
                                $updateStmt->bind_param('sii', $token, $expiry, $userId);
                                $updateStmt->execute();
                                $updateStmt->close();
                            }
                        }
                        
                        // Update last login time
                        $updateLogin = $conn->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
                        if ($updateLogin) {
                            $updateLogin->bind_param('i', $userId);
                            $updateLogin->execute();
                            $updateLogin->close();
                        }
                        
                        $stmt->close();
                        
                        // Redirect to intended page or dashboard
                        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'dashboard.php';
                        header('Location: ' . $redirect);
                        exit;
                    } else {
                        $error = 'Invalid email or password.';
                    }
                } else {
                    $error = 'Invalid email or password.';
                }
                $stmt->close();
            } else {
                $error = 'Database error. Please try again later.';
            }
        }
    } else {
        $error = 'Please enter both email and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Login to your Dermaluxe account for personalized skincare solutions">
  <title>Dermaluxe | Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --red-side: #C84B31;
      --red-dark: #A63E28;
      --btn-bg: #C84B31;
      --btn-hover: #A63E28;
      --bg-light: #FFF8F5;
      --input-bg: #FFFFFF;
      --input-border: #E8D5CF;
      --input-focus: #C84B31;
      --text-dark: #2D2424;
      --text-light: #6C6060;
      --error-color: #dc3545;
      --success-color: #28a745;
      --border-color: #E8D5CF;
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
      box-shadow: var(--shadow);
      padding: 1.5rem 0;
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
      color: var(--red-side);
      font-size: 1.8rem;
      font-weight: 700;
      letter-spacing: 2px;
      cursor: pointer;
      transition: var(--transition);
    }

    .logo h1:hover {
      transform: scale(1.05);
    }

    .nav {
      display: flex;
      gap: 2rem;
      align-items: center;
    }

    .nav a {
      text-decoration: none;
      color: var(--text-dark);
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

    /* Main Content */
    .main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4rem 0;
    }

    .login-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      max-width: 1100px;
      width: 100%;
      align-items: center;
    }

    .login-hero {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .login-hero h2 {
      font-size: 3rem;
      font-weight: 700;
      color: var(--text-dark);
      line-height: 1.2;
    }

    .login-hero .highlight {
      color: var(--red-side);
    }

    .login-hero p {
      font-size: 1.2rem;
      color: var(--text-light);
      line-height: 1.8;
    }

    .features-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin-top: 1rem;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .feature-item:hover {
      transform: translateX(10px);
      box-shadow: var(--shadow-hover);
    }

    .feature-icon {
      width: 50px;
      height: 50px;
      background: var(--bg-light);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      flex-shrink: 0;
    }

    .feature-text {
      font-weight: 500;
      color: var(--text-dark);
    }

    .login-card {
      background: white;
      padding: 3rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow-hover);
      border: 1px solid var(--border-color);
      animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-card h3 {
      text-align: center;
      margin-bottom: 0.5rem;
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-dark);
    }

    .login-subtitle {
      text-align: center;
      color: var(--text-light);
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }

    .alert {
      padding: 12px 16px;
      border-radius: var(--radius);
      margin-bottom: 1.5rem;
      text-align: center;
      font-size: 0.9rem;
      animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-error {
      background: rgba(220, 53, 69, 0.1);
      color: var(--error-color);
      border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .alert-success {
      background: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
      border: 1px solid rgba(40, 167, 69, 0.2);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.95rem;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
      font-size: 1.2rem;
    }

    .form-group input {
      width: 100%;
      padding: 14px 16px 14px 45px;
      border: 2px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      transition: var(--transition);
      color: var(--text-dark);
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--input-focus);
      box-shadow: 0 0 0 4px rgba(200, 75, 49, 0.1);
    }

    .form-group input::placeholder {
      color: #aaa;
    }

    .password-toggle {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
      cursor: pointer;
      font-size: 1.2rem;
      transition: var(--transition);
      user-select: none;
    }

    .password-toggle:hover {
      color: var(--red-side);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      font-size: 0.9rem;
      color: var(--text-dark);
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--red-side);
    }

    .forgot-link {
      color: var(--red-side);
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .forgot-link:hover {
      color: var(--red-dark);
      text-decoration: underline;
    }

    .btn {
      width: 100%;
      padding: 16px;
      border: none;
      border-radius: var(--radius);
      font-size: 1.1rem;
      font-weight: 700;
      cursor: pointer;
      transition: var(--transition);
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .btn-primary {
      background: var(--btn-bg);
      color: white;
      position: relative;
      overflow: hidden;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn-primary:hover::before {
      width: 300px;
      height: 300px;
    }

    .btn-primary:hover {
      background: var(--btn-hover);
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .btn:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none !important;
    }

    .btn-text {
      position: relative;
      z-index: 1;
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 2rem 0;
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid var(--border-color);
    }

    .divider span {
      padding: 0 1rem;
    }

    .register-link {
      text-align: center;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border-color);
    }

    .register-link p {
      color: var(--text-light);
      font-size: 0.95rem;
    }

    .register-link a {
      color: var(--red-side);
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
    }

    .register-link a:hover {
      color: var(--red-dark);
      text-decoration: underline;
    }

    /* Loading Spinner */
    .spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 0.8s linear infinite;
      margin-right: 8px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Footer */
    .footer {
      background: var(--text-dark);
      color: white;
      padding: 3rem 0 1.5rem;
      margin-top: auto;
    }

    .footer-sections {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
      padding-bottom: 2rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-section h3 {
      color: white;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 1px;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section li {
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
      background: var(--red-dark);
    }

    .footer-bottom {
      text-align: center;
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 968px) {
      .login-container {
        grid-template-columns: 1fr;
        gap: 3rem;
      }

      .login-hero {
        text-align: center;
      }

      .login-hero h2 {
        font-size: 2.5rem;
      }

      .feature-item:hover {
        transform: translateY(-5px);
      }
    }

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

      .login-card {
        padding: 2rem;
      }

      .login-hero h2 {
        font-size: 2rem;
      }

      .footer-sections {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .main {
        padding: 2rem 0;
      }

      .form-options {
        flex-direction: column;
        align-items: flex-start;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 0 15px;
      }

      .login-card {
        padding: 1.5rem;
      }

      .nav a {
        font-size: 0.9rem;
      }

      .login-hero h2 {
        font-size: 1.75rem;
      }

      .btn {
        padding: 14px;
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="logo" onclick="window.location.href='index.php'">
        <h1>DERMALUXE</h1>
      </div>
      <nav class="nav">
        <a href="shop.php">Shop</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="stores.php">Stores</a>
        <a href="faqs.php">FAQs</a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <div class="login-container">
        <!-- Hero Section -->
        <div class="login-hero">
          <h2>Welcome Back to <span class="highlight">Dermaluxe</span></h2>
          <p>Sign in to access your personalized skincare journey and exclusive member benefits.</p>
          
          <div class="features-list">
            <div class="feature-item">
              <div class="feature-icon">✨</div>
              <div class="feature-text">Personalized Product Recommendations</div>
            </div>
            <div class="feature-item">
              <div class="feature-icon">📊</div>
              <div class="feature-text">Track Your Skin Progress</div>
            </div>
            <div class="feature-item">
              <div class="feature-icon">🎁</div>
              <div class="feature-text">Exclusive Member Discounts</div>
            </div>
            <div class="feature-item">
              <div class="feature-icon">💬</div>
              <div class="feature-text">Expert Skincare Consultations</div>
            </div>
          </div>
        </div>

        <!-- Login Form -->
        <div class="login-card">
          <h3>Sign In</h3>
          <p class="login-subtitle">Enter your credentials to continue</p>
          
          <?php if ($error): ?>
            <div class="alert alert-error">
              <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success">
              <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="login.php" class="login-form" id="loginForm">
            <div class="form-group">
              <label for="email">Email Address</label>
              <div class="input-wrapper">
                <span class="input-icon">📧</span>
                <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  required 
                  placeholder="Enter your email"
                  value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                  autocomplete="email"
                >
              </div>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <div class="input-wrapper">
                <span class="input-icon">🔒</span>
                <input 
                  type="password" 
                  id="password" 
                  name="password" 
                  required 
                  placeholder="Enter your password"
                  autocomplete="current-password"
                >
                <span class="password-toggle" id="togglePassword">👁️</span>
              </div>
            </div>

            <div class="form-options">
              <label class="remember-me">
                <input type="checkbox" name="remember" id="rememberMe">
                <span>Remember me</span>
              </label>
              <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">
              <span class="btn-text">Sign In</span>
            </button>

            <div class="divider">
              <span>OR</span>
            </div>

            <div class="register-link">
              <p>Don't have an account? <a href="register.php">Create one now</a></p>
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
            <li><a href="search.php">Search</a></li>
            <li><a href="stores.php">Store Locator</a></li>
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
            <li><a href="terms.php">Terms and Conditions</a></li>
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
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.textContent = type === 'password' ? '👁️' : '🔓';
    });

    // Form submission with loading state
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');

    loginForm.addEventListener('submit', function(e) {
      // Disable button and show loading state
      submitBtn.disabled = true;
      btnText.innerHTML = '<span class="spinner"></span>Signing in...';
    });

    // Email validation on blur
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (this.value && !emailPattern.test(this.value)) {
        this.style.borderColor = 'var(--error-color)';
      } else {
        this.style.borderColor = 'var(--input-border)';
      }
    });

    // Auto-hide success/error messages after 5 seconds
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);

    // Enter key navigation
    document.querySelectorAll('input').forEach((input, index, inputs) => {
      input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && index < inputs.length - 1) {
          e.preventDefault();
          inputs[index + 1].focus();
        }
      });
    });

    // Focus first input on page load
    window.addEventListener('load', () => {
      emailInput.focus();
    });
  </script>
</body>
</html>