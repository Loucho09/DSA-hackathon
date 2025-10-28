<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Ordering Process</title>
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
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="dashboard.php" class="nav-btn">Dashboard</a>
        <?php else: ?>
          <a href="login.php" class="nav-btn">Login</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <div class="page-header">
        <h1>Ordering Process</h1>
        <p class="subtitle">How to place and track your Dermaluxe orders</p>
      </div>

      <div class="process-steps">
        <div class="process-step">
          <div class="step-visual">
            <div class="step-icon">1</div>
            <div class="step-connector"></div>
          </div>
          <div class="step-content">
            <h3>Browse Products</h3>
            <p>Explore our complete range of skincare products. Use filters to find items suitable for your skin type and concerns.</p>
            <ul>
              <li>View product details and ingredients</li>
              <li>Read customer reviews</li>
              <li>Check availability and pricing</li>
            </ul>
          </div>
        </div>

        <div class="process-step">
          <div class="step-visual">
            <div class="step-icon">2</div>
            <div class="step-connector"></div>
          </div>
          <div class="step-content">
            <h3>Add to Cart</h3>
            <p>Select your desired products and add them to your shopping cart. You can modify quantities or remove items at any time.</p>
            <ul>
              <li>Choose product size and quantity</li>
              <li>View cart total and item count</li>
              <li>Apply promo codes if available</li>
            </ul>
          </div>
        </div>

        <div class="process-step">
          <div class="step-visual">
            <div class="step-icon">3</div>
            <div class="step-connector"></div>
          </div>
          <div class="step-content">
            <h3>Checkout</h3>
            <p>Proceed to checkout where you'll enter your shipping information and payment details.</p>
            <ul>
              <li>Create account or checkout as guest</li>
              <li>Enter shipping address</li>
              <li>Select delivery method</li>
              <li>Choose payment option</li>
            </ul>
          </div>
        </div>

        <div class="process-step">
          <div class="step-visual">
            <div class="step-icon">4</div>
            <div class="step-connector"></div>
          </div>
          <div class="step-content">
            <h3>Order Confirmation</h3>
            <p>After successful payment, you'll receive an order confirmation with all details and estimated delivery date.</p>
            <ul>
              <li>Email confirmation sent immediately</li>
              <li>Order number for tracking</li>
              <li>Invoice and receipt</li>
            </ul>
          </div>
        </div>

        <div class="process-step">
          <div class="step-visual">
            <div class="step-icon">5</div>
          </div>
          <div class="step-content">
            <h3>Delivery & Tracking</h3>
            <p>Track your order in real-time and receive notifications about delivery status.</p>
            <ul>
              <li>Tracking number provided</li>
              <li>Real-time updates</li>
              <li>Delivery notifications</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="shipping-info">
        <h2>Shipping Information</h2>
        <div class="shipping-grid">
          <div class="shipping-card">
            <h3>🚚 Standard Shipping</h3>
            <p><strong>3-5 business days</strong></p>
            <p>Free on orders over $50</p>
            <p class="price">$5.99 for orders under $50</p>
          </div>
          <div class="shipping-card">
            <h3>⚡ Express Shipping</h3>
            <p><strong>1-2 business days</strong></p>
            <p>Order before 2 PM for same-day dispatch</p>
            <p class="price">$12.99</p>
          </div>
          <div class="shipping-card">
            <h3>🏠 International</h3>
            <p><strong>7-14 business days</strong></p>
            <p>Available to 50+ countries</p>
            <p class="price">$24.99</p>
          </div>
        </div>
      </div>

      <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-grid">
          <div class="faq-item">
            <h3>Can I modify my order after placing it?</h3>
            <p>Orders can be modified within 1 hour of placement. Contact our customer service immediately for changes.</p>
          </div>
          <div class="faq-item">
            <h3>What payment methods do you accept?</h3>
            <p>We accept all major credit cards, PayPal, Apple Pay, and Google Pay.</p>
          </div>
          <div class="faq-item">
            <h3>Do you offer international shipping?</h3>
            <p>Yes, we ship to over 50 countries worldwide. Shipping costs and delivery times vary by location.</p>
          </div>
          <div class="faq-item">
            <h3>How can I track my order?</h3>
            <p>You'll receive a tracking number via email once your order ships. You can also track it in your account dashboard.</p>
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
      align-items: center;
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

    .nav-btn {
      background: var(--btn-bg);
      color: white !important;
      padding: 8px 16px;
      border-radius: var(--radius);
      transition: var(--transition);
    }

    .nav-btn:hover {
      background: var(--btn-hover);
      transform: translateY(-2px);
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 3rem 0;
    }

    .page-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .page-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--text-dark);
    }

    .subtitle {
      font-size: 1.2rem;
      color: var(--text-light);
    }

    /* Process Steps */
    .process-steps {
      background: white;
      padding: 2.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
      border: 1px solid var(--border-color);
    }

    .process-step {
      display: flex;
      gap: 2rem;
      margin-bottom: 3rem;
      position: relative;
    }

    .process-step:last-child {
      margin-bottom: 0;
    }

    .step-visual {
      display: flex;
      flex-direction: column;
      align-items: center;
      flex-shrink: 0;
    }

    .step-icon {
      background: var(--red-side);
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 700;
      z-index: 2;
    }

    .step-connector {
      width: 2px;
      background: var(--border-color);
      flex: 1;
      margin-top: 1rem;
    }

    .step-content h3 {
      color: var(--red-side);
      margin-bottom: 1rem;
      font-size: 1.4rem;
    }

    .step-content p {
      color: var(--text-light);
      margin-bottom: 1rem;
    }

    .step-content ul {
      list-style: none;
      padding-left: 0;
    }

    .step-content li {
      padding: 0.25rem 0;
      color: var(--text-light);
      position: relative;
      padding-left: 1.5rem;
    }

    .step-content li:before {
      content: "✓";
      color: var(--btn-bg);
      font-weight: bold;
      position: absolute;
      left: 0;
    }

    /* Shipping Info */
    .shipping-info {
      background: white;
      padding: 2.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
      border: 1px solid var(--border-color);
    }

    .shipping-info h2 {
      color: var(--red-side);
      margin-bottom: 2rem;
      font-size: 1.8rem;
      font-weight: 600;
      text-align: center;
    }

    .shipping-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .shipping-card {
      background: var(--bg-light);
      padding: 2rem;
      border-radius: var(--radius);
      text-align: center;
      border: 2px solid transparent;
      transition: var(--transition);
    }

    .shipping-card:hover {
      border-color: var(--btn-bg);
      transform: translateY(-5px);
    }

    .shipping-card h3 {
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }

    .shipping-card p {
      margin-bottom: 0.5rem;
      color: var(--text-light);
    }

    .price {
      color: var(--btn-bg) !important;
      font-weight: 600;
      font-size: 1.1rem;
    }

    /* FAQ Section */
    .faq-section {
      background: white;
      padding: 2.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .faq-section h2 {
      color: var(--red-side);
      margin-bottom: 2rem;
      font-size: 1.8rem;
      font-weight: 600;
      text-align: center;
    }

    .faq-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }

    .faq-item {
      background: var(--bg-light);
      padding: 1.5rem;
      border-radius: var(--radius);
      border-left: 4px solid var(--btn-bg);
    }

    .faq-item h3 {
      margin-bottom: 0.75rem;
      font-size: 1.1rem;
      color: var(--text-dark);
    }

    .faq-item p {
      color: var(--text-light);
      line-height: 1.5;
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

      .process-step {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
      }

      .step-visual {
        flex-direction: row;
        justify-content: center;
        gap: 1rem;
      }

      .step-connector {
        width: 100px;
        height: 2px;
        margin-top: 0;
      }

      .process-steps,
      .shipping-info,
      .faq-section {
        padding: 1.5rem;
      }

      .footer-sections {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .page-header h1 {
        font-size: 2rem;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 0 15px;
      }

      .nav {
        gap: 0.75rem;
      }

      .nav a {
        font-size: 0.9rem;
      }

      .shipping-grid,
      .faq-grid {
        grid-template-columns: 1fr;
      }

      .step-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
      }
    }
  </style>
</body>
</html>