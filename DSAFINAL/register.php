<?php
require_once __DIR__ . '/db.php';

$error = '';
$success = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';

    if ($fullName === '' || $email === '' || $password === '') {
        $error = 'Please fill in all fields.';
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
                        $success = 'Registered successfully!';
                    } else {
                        $error = 'Registration failed.';
                    }
                    $stmt->close();
                } else {
                    $error = 'Server error.';
                }
            }
            $check->close();
        } else {
            $error = 'Server error.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
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
      <!-- Register Form -->
      <div class="form register-form visible" id="registerForm">
        <h2>Create Your Account</h2>
        <?php if ($error) { echo '<p style="color:red; text-align:center; margin-bottom:10px;">' . htmlspecialchars($error) . '</p>'; } ?>
        <?php if ($success) { echo '<p style="color:green; text-align:center; margin-bottom:10px;">' . htmlspecialchars($success) . ' <a href="login.php">Login</a></p>'; } ?>
        <form id="registerSubmitForm" action="register.php" method="post">
          <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="full_name" required>
          </div>
          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
          </div>

          <div class="form-group">
            <label>Password:</label>
            <input type="password" id="registerPassword" name="password" required>

            <div class="show-password">
              <input type="checkbox" id="toggleRegisterPassword">
              <label for="toggleRegisterPassword">Show Password</label>
            </div>
          </div>
          <button type="submit" class="btn">Register</button>
        </form>
      </div>

      <!-- Login Form (link to separate page) -->
      <div class="form login-form hidden" id="loginForm">
        <h2>Login to Your Account</h2>
        <div class="form-group">
          <label>Already have an account?</label>
        </div>
        <button class="btn" id="goToLogin">Go to Login</button>
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
    const goToLogin = document.getElementById('goToLogin');

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

    showLoginBtn.addEventListener('click', () => {
      window.location.href = 'login.php';
    });
    showRegisterBtn.addEventListener('click', () => {
      // already on register
    });
    if (goToLogin) {
      goToLogin.addEventListener('click', () => {
        window.location.href = 'login.php';
      });
    }

    const toggleRegisterPassword = document.getElementById('toggleRegisterPassword');
    const registerPassword = document.getElementById('registerPassword');
    toggleRegisterPassword.addEventListener('change', () => {
      registerPassword.type = toggleRegisterPassword.checked ? 'text' : 'password';
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
