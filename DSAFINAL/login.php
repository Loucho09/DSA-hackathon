<?php
session_start();
require_once __DIR__ . '/db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';

    if ($email !== '' && $password !== '') {
        $stmt = $conn->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 1) {
                $stmt->bind_result($userId, $passwordHash);
                $stmt->fetch();
                if (password_verify($password, $passwordHash)) {
                    $_SESSION['user_id'] = $userId;
                    header('Location: dashboard.php?authed=1');
                    exit;
                }
            }
            $stmt->close();
        }
        $error = 'Invalid email or password.';
    } else {
        $error = 'Please enter email and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
</head>

<body>
  <div class="backdrop" id="backdrop"></div>
  <div class="container" id="loginContainer">
    <button class="close-btn" id="closeBtn">&times;</button>

    <!-- Left Side -->
    <div class="left-panel">
      <img src="pringles-seeklogo.png" alt="Logoo" class="logo">

      <div class="switch-btns">
        <button class="switch" id="showLogin">Log In</button>
        <button class="switch" id="showRegister">Register</button>
      </div>
    </div>

    <!-- Right Side -->
    <div class="right-panel">
      <!-- Login Form -->
      <div class="form login-form visible" id="loginForm">
        <h2>Login to Your Account</h2>
        <?php if ($error) { echo '<p style="color:red; text-align:center; margin-bottom:10px;">' . htmlspecialchars($error) . '</p>'; } ?>
        <form id="loginSubmitForm" action="login.php" method="post">
          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
          </div>
          
          <div class="form-group">
            <label>Password:</label>
            <input type="password" id="loginPassword" name="password" required>

            <div class="show-password">
              <input type="checkbox" id="toggleLoginPassword">
              <label for="toggleLoginPassword">Show Password</label>
            </div>
          </div>
          <button type="submit" class="btn">Login</button>
        </form>
      </div>

      <!-- Register Form (link to separate page) -->
      <div class="form register-form hidden" id="registerForm">
        <h2>Create Your Account</h2>
        <div class="form-group">
          <label>Continue to registration</label>
        </div>
        <button class="btn" id="goToRegister">Go to Register</button>
      </div>
    </div>
  </div>

  <!-- Functionalities -->
  <script>
    const showLoginBtn = document.getElementById('showLogin');
    const showRegisterBtn = document.getElementById('showRegister');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const closeBtn = document.getElementById('closeBtn');
    const container = document.getElementById('loginContainer');
    const goToRegister = document.getElementById('goToRegister');

    closeBtn.addEventListener('click', () => {
      container.classList.add('fade-out');
      document.getElementById('backdrop').classList.add('fade-out');
      setTimeout(() => {
        if (window.history.length > 1) {
          window.history.back();
        } else {
          window.location.href = 'dashboard.html';
        }
      }, 300);
    });

    showRegisterBtn.addEventListener('click', () => {
      window.location.href = 'register.php';
    });
    if (goToRegister) {
      goToRegister.addEventListener('click', () => {
        window.location.href = 'register.php';
      });
    }

    showLoginBtn.addEventListener('click', () => {
      // already on login
    });

    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginPassword = document.getElementById('loginPassword');
    toggleLoginPassword.addEventListener('change', () => {
      loginPassword.type = toggleLoginPassword.checked ? 'text' : 'password';
    });
  </script>
</body>

<style>
  :root {
    --red-side: #8F1402;
    --btn-bg: #006336;
    --input-bg: #f1f3f5;
    --input-border: #ced4da;
    --text-dark: #333;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    background: transparent;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 1;
    transition: opacity 0.3s ease;
    z-index: 5;
  }

  .container {
    display: flex;
    width: 850px;
    height: 500px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    position: relative;
    opacity: 1;
    transition: opacity 0.5s ease;
    z-index: 10;
  }

  .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 28px;
    color: #555;
    cursor: pointer;
    z-index: 10;
    transition: color 0.2s ease;
  }

  .close-btn:hover {
    color: #000;
  }

  .container.fade-out {
    opacity: 0;
    pointer-events: none;
  }

  .backdrop.fade-out {
    opacity: 0;
    pointer-events: none;
  }

  .logo {
    width: 120px;
    height: auto;
    margin-bottom: 1px;
  }

  .left-panel {
    flex: 1;
    background: var(--red-side);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    text-align: center;
  }

  .left-panel .switch-btns {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 1rem;
  }

  button.switch {
    background: white;
    color: var(--red-side);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 150px;
    transition: all 0.4s ease;
  }

  button.switch::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg, #fff 0%, #ffbaba 50%, #fff 100%);
    transition: all 0.4s ease;
  }

  button.switch:hover::before {
    left: 100%;
  }

  button.switch:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.4);
  }

  .right-panel {
    flex: 1.2;
    background: #fff;
    padding: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  .form {
    position: absolute;
    width: 80%;
    transition: opacity 0.4s ease, transform 0.4s ease;
  }

  .form.hidden {
    opacity: 0;
    transform: translateY(30px);
    pointer-events: none;
  }

  .form.visible {
    opacity: 1;
    transform: translateY(0);
    pointer-events: all;
  }

  .form h2 {
    color: var(--text-dark);
    text-align: center;
    margin-bottom: 2rem;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
  }

  input[type="email"],
  input[type="password"],
  input[type="text"] {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--input-border);
    border-radius: 6px;
    background: var(--input-bg);
    font-size: 1rem;
  }

  .show-password {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-dark);
    margin-top: 0.3rem;
  }

  .btn {
    width: 100%;
    padding: 0.75rem;
    background: var(--btn-bg);
    color: white;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-top: 1rem;
  }

  .btn:hover {
    background: #0db3b3;
  }
</style>
</html>
