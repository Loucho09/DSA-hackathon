<?php
session_start();
require_once __DIR__ . '/db.php';

$is_logged_in = isset($_SESSION['user_id']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message_text = $_POST['message'] ?? '';
    
    if ($stmt = $conn->prepare('INSERT INTO contact_messages (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())')) {
        $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message_text);
        if ($stmt->execute()) {
            $message = 'success';
        } else {
            $message = 'error';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dermaluxe | Contact Us</title>
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

    /* Header */
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

    .nav a:hover::after,
    .nav a.active::after {
      width: 100%;
    }

    /* Hero */
    .hero {
      background: linear-gradient(135deg, var(--red-side), #A63E28);
      color: white;
      padding: 5rem 0;
      text-align: center;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .hero p {
      font-size: 1.2rem;
      opacity: 0.95;
    }

    /* Contact Content */
    .contact-content {
      padding: 5rem 0;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
    }

    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .contact-info h2 {
      font-size: 2rem;
      color: var(--red-side);
      margin-bottom: 1rem;
    }

    .contact-info p {
      color: var(--text-light);
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    .info-card {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .info-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    .info-card-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .info-icon {
      width: 50px;
      height: 50px;
      background: var(--bg-light);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }

    .info-card h3 {
      color: var(--text-dark);
      font-size: 1.2rem;
    }

    .info-card p {
      color: var(--text-light);
      margin: 0;
      font-size: 1rem;
    }

    .info-card a {
      color: var(--red-side);
      text-decoration: none;
      font-weight: 500;
    }

    /* Contact Form */
    .contact-form-container {
      background: white;
      padding: 3rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .contact-form-container h2 {
      font-size: 2rem;
      color: var(--red-side);
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--border-color);
      border-radius: var(--radius);
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      transition: var(--transition);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--red-side);
      box-shadow: 0 0 0 3px rgba(200, 75, 49, 0.1);
    }

    .form-group textarea {
      min-height: 150px;
      resize: vertical;
    }

    .submit-btn {
      width: 100%;
      padding: 14px;
      background: var(--red-side);
      color: white;
      border: none;
      border-radius: var(--radius);
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    .submit-btn:hover {
      background: #A63E28;
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .alert {
      padding: 1rem 1.5rem;
      border-radius: var(--radius);
      margin-bottom: 2rem;
      animation: slideIn 0.5s ease;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    /* Map Section */
    .map-section {
      background: white;
      padding: 5rem 0;
    }

    .map-container {
      width: 100%;
      height: 450px;
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      background: var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-light);
    }

    /* FAQ Quick Links */
    .faq-quick {
      background: var(--bg-light);
      padding: 3rem;
      border-radius: var(--radius);
      text-align: center;
      margin: 3rem 0;
    }

    .faq-quick h3 {
      font-size: 1.5rem;
      color: var(--text-dark);
      margin-bottom: 1rem;
    }

    .faq-quick p {
      color: var(--text-light);
      margin-bottom: 1.5rem;
    }

    .faq-btn {
      display: inline-block;
      padding: 12px 32px;
      background: white;
      color: var(--red-side);
      border: 2px solid var(--red-side);
      border-radius: var(--radius);
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
    }

    .faq-btn:hover {
      background: var(--red-side);
      color: white;
    }

    /* Footer */
    .footer {
      background: var(--text-dark);
      color: white;
      padding: 3rem 0 1.5rem;
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

    .footer-bottom {
      text-align: center;
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.9rem;
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

    @media (max-width: 968px) {
      .contact-content {
        grid-template-columns: 1fr;
        gap: 2rem;
      }

      .hero h1 {
        font-size: 2rem;
      }

      .nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="logo">
        <h1>DERMALUXE</h1>
      </div>
      <nav class="nav">
        <a href="shopall.php">Shop</a>
        <a href="about.php">About Us</a>
        <a href="contact.php" class="active">Contact Us</a>
        <a href="store-locator.php">Stores</a>
        <a href="faqs.php">FAQs</a>
        <?php if ($is_logged_in): ?>
          <a href="dashboard.php">Dashboard</a>
        <?php else: ?>
          <a href="login.php">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <section class="hero">
    <div class="container">
      <h1>Get in Touch</h1>
      <p>We're here to help with any questions about our products or your skincare journey</p>
    </div>
  </section>

  <section class="contact-content">
    <div class="container" style="grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
      <div class="contact-info">
        <div>
          <h2>Contact Information</h2>
          <p>Have a question? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>

        <div class="info-card">
          <div class="info-card-header">
            <div class="info-icon">📧</div>
            <h3>Email Us</h3>
          </div>
          <p><a href="mailto:support@dermaluxe.com">support@dermaluxe.com</a></p>
          <p style="font-size: 0.9rem; margin-top: 0.5rem;">We'll respond within 24 hours</p>
        </div>

        <div class="info-card">
          <div class="info-card-header">
            <div class="info-icon">📞</div>
            <h3>Call Us</h3>
          </div>
          <p><a href="tel:+639123456789">+63 912 345 6789</a></p>
          <p style="font-size: 0.9rem; margin-top: 0.5rem;">Mon-Fri: 9AM - 6PM (PHT)</p>
        </div>

        <div class="info-card">
          <div class="info-card-header">
            <div class="info-icon">📍</div>
            <h3>Visit Us</h3>
          </div>
          <p>123 Skincare Avenue<br>Quezon City, Metro Manila<br>Philippines 1100</p>
        </div>

        <div class="info-card">
          <div class="info-card-header">
            <div class="info-icon">💬</div>
            <h3>Live Chat</h3>
          </div>
          <p>Available on our website</p>
          <p style="font-size: 0.9rem; margin-top: 0.5rem;">Mon-Sat: 10AM - 8PM (PHT)</p>
        </div>
      </div>

      <div class="contact-form-container">
        <h2>Send Us a Message</h2>
        
        <?php if ($message === 'success'): ?>
          <div class="alert alert-success">
            Thank you for contacting us! We've received your message and will get back to you soon.
          </div>
        <?php elseif ($message === 'error'): ?>
          <div class="alert alert-error">
            Sorry, there was an error sending your message. Please try again.
          </div>
        <?php endif; ?>

        <form method="POST" id="contactForm">
          <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" required>
          </div>

          <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required>
          </div>

          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone">
          </div>

          <div class="form-group">
            <label for="subject">Subject *</label>
            <select id="subject" name="subject" required>
              <option value="">Select a subject</option>
              <option value="Product Inquiry">Product Inquiry</option>
              <option value="Order Status">Order Status</option>
              <option value="Skin Consultation">Skin Consultation</option>
              <option value="Partnership">Partnership Opportunity</option>
              <option value="Feedback">Feedback</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <div class="form-group">
            <label for="message">Message *</label>
            <textarea id="message" name="message" required placeholder="Tell us how we can help you..."></textarea>
          </div>

          <button type="submit" class="submit-btn">Send Message</button>
        </form>
      </div>
    </div>
  </section>

  <section class="map-section" style="padding: 2rem 0;">
  <div class="container" style="max-width: 900px; margin: 0 auto;">
    <h2 style="text-align: center; font-size: 2rem; color: var(--red-side); margin-bottom: 2rem;">
      Find Our Main Office
    </h2>

    <div class="map-container" style="text-align: center;">
      <p style="font-size: 1.2rem; margin-bottom: 1rem;">
        📍 Map View - 123 Skincare Avenue, Quezon City
      </p>

      <iframe
        src="https://www.google.com/maps?q=123+Skincare+Avenue,+Quezon+City&output=embed"
        width="100%"
        height="400"
        style="border:0; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</section>


  <section class="container">
    <div class="faq-quick">
      <h3>Looking for Quick Answers?</h3>
      <p>Check out our Frequently Asked Questions for instant answers to common inquiries</p>
      <a href="faqs.php" class="faq-btn">Visit FAQs</a>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="footer-sections">
        <div class="footer-section">
          <h3>RESOURCES</h3>
          <ul>
            <li><a href="size-guide.php">Size Guide</a></li>
            <li><a href="ordering-process.php">Ordering Process</a></li>
            <li><a href="care-guide.php">Care Guide</a></li>
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
          </ul>
        </div>

        <div class="footer-section">
          <h3>SUPPORT</h3>
          <ul>
            <li><a href="faqs.php">FAQs</a></li>
            <li><a href="support.php">Help Center</a></li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; 2025 Dermaluxe. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    // Auto-hide success message
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);

    // Form validation
    document.getElementById('contactForm').addEventListener('submit', function(e) {
      const phone = document.getElementById('phone').value;
      if (phone && !/^[\d\s\-\+\(\)]+$/.test(phone)) {
        e.preventDefault();
        alert('Please enter a valid phone number');
        return;
      }

      const message = document.getElementById('message').value;
      if (message.length < 10) {
        e.preventDefault();
        alert('Please provide more details in your message (minimum 10 characters)');
        return;
      }
    });
  </script>
</body>
</html>