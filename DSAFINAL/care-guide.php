<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Care Guide</title>
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
        <h1>Skincare Care Guide</h1>
        <p class="subtitle">Learn how to properly care for your Dermaluxe products</p>
      </div>

      <div class="care-guide">
        <div class="guide-section">
          <h2>Daily Skincare Routine</h2>
          <div class="routine-steps">
            <div class="step">
              <div class="step-number">1</div>
              <div class="step-content">
                <h3>Cleansing</h3>
                <p>Start with our Gentle Cleanser to remove impurities and prepare your skin for treatment.</p>
                <ul>
                  <li>Use lukewarm water</li>
                  <li>Massage gently for 60 seconds</li>
                  <li>Rinse thoroughly</li>
                </ul>
              </div>
            </div>

            <div class="step">
              <div class="step-number">2</div>
              <div class="step-content">
                <h3>Toning</h3>
                <p>Apply our Balancing Toner to restore pH balance and refine pores.</p>
                <ul>
                  <li>Apply with cotton pad or hands</li>
                  <li>Focus on T-zone areas</li>
                  <li>Let absorb completely</li>
                </ul>
              </div>
            </div>

            <div class="step">
              <div class="step-number">3</div>
              <div class="step-content">
                <h3>Treatment</h3>
                <p>Use targeted serums based on your skin concerns.</p>
                <ul>
                  <li>Vitamin C serum in AM for brightness</li>
                  <li>Retinol serum in PM for anti-aging</li>
                  <li>Hyaluronic Acid for hydration</li>
                </ul>
              </div>
            </div>

            <div class="step">
              <div class="step-number">4</div>
              <div class="step-content">
                <h3>Moisturizing</h3>
                <p>Lock in moisture with our Hydrating Cream.</p>
                <ul>
                  <li>Apply to face and neck</li>
                  <li>Use upward motions</li>
                  <li>Don't forget your décolletage</li>
                </ul>
              </div>
            </div>

            <div class="step">
              <div class="step-number">5</div>
              <div class="step-content">
                <h3>Sun Protection</h3>
                <p>Always finish with SPF 30+ in your morning routine.</p>
                <ul>
                  <li>Apply 15 minutes before sun exposure</li>
                  <li>Reapply every 2 hours</li>
                  <li>Use even on cloudy days</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="guide-section">
          <h2>Product Storage Tips</h2>
          <div class="tips-grid">
            <div class="tip-card">
              <h3>🌡️ Temperature</h3>
              <p>Store products in a cool, dry place away from direct sunlight and heat sources.</p>
            </div>
            <div class="tip-card">
              <h3>🧴 Containers</h3>
              <p>Keep lids tightly closed to prevent oxidation and contamination.</p>
            </div>
            <div class="tip-card">
              <h3>⏰ Shelf Life</h3>
              <p>Most products last 6-12 months after opening. Check PAO symbol on packaging.</p>
            </div>
            <div class="tip-card">
              <h3>🚫 Contamination</h3>
              <p>Use clean hands or applicators. Never add water to products.</p>
            </div>
          </div>
        </div>

        <div class="guide-section">
          <h2>Skin Type Recommendations</h2>
          <div class="skin-types">
            <div class="skin-type">
              <h3>Dry Skin</h3>
              <ul>
                <li>Use cream-based cleansers</li>
                <li>Layer hydrating serums</li>
                <li>Rich moisturizers morning and night</li>
                <li>Avoid alcohol-based products</li>
              </ul>
            </div>
            <div class="skin-type">
              <h3>Oily Skin</h3>
              <ul>
                <li>Gel or foam cleansers</li>
                <li>Oil-free moisturizers</li>
                <li>Salicylic acid treatments</li>
                <li>Clay masks 1-2 times weekly</li>
              </ul>
            </div>
            <div class="skin-type">
              <h3>Combination Skin</h3>
              <ul>
                <li>Balance different areas</li>
                <li>Lightweight moisturizers</li>
                <li>Spot treat oily zones</li>
                <li>Hydrate dry areas separately</li>
              </ul>
            </div>
            <div class="skin-type">
              <h3>Sensitive Skin</h3>
              <ul>
                <li>Fragrance-free products</li>
                <li>Patch test new products</li>
                <li>Gentle, minimal routine</li>
                <li>Avoid physical exfoliants</li>
              </ul>
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

    /* Care Guide Sections */
    .guide-section {
      background: white;
      padding: 2.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
      border: 1px solid var(--border-color);
    }

    .guide-section h2 {
      color: var(--red-side);
      margin-bottom: 2rem;
      font-size: 1.8rem;
      font-weight: 600;
    }

    /* Routine Steps */
    .routine-steps {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .step {
      display: flex;
      gap: 1.5rem;
      align-items: flex-start;
    }

    .step-number {
      background: var(--red-side);
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      flex-shrink: 0;
    }

    .step-content h3 {
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-size: 1.2rem;
    }

    .step-content p {
      color: var(--text-light);
      margin-bottom: 0.5rem;
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
      content: "•";
      color: var(--red-side);
      font-weight: bold;
      position: absolute;
      left: 0;
    }

    /* Tips Grid */
    .tips-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .tip-card {
      background: var(--bg-light);
      padding: 1.5rem;
      border-radius: var(--radius);
      border-left: 4px solid var(--btn-bg);
    }

    .tip-card h3 {
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
    }

    /* Skin Types */
    .skin-types {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .skin-type {
      background: var(--bg-light);
      padding: 1.5rem;
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
    }

    .skin-type h3 {
      color: var(--red-side);
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }

    .skin-type ul {
      list-style: none;
      padding-left: 0;
    }

    .skin-type li {
      padding: 0.25rem 0;
      color: var(--text-light);
      position: relative;
      padding-left: 1.5rem;
    }

    .skin-type li:before {
      content: "✓";
      color: var(--btn-bg);
      font-weight: bold;
      position: absolute;
      left: 0;
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

      .guide-section {
        padding: 1.5rem;
      }

      .step {
        flex-direction: column;
        text-align: center;
      }

      .step-number {
        align-self: center;
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

      .tips-grid,
      .skin-types {
        grid-template-columns: 1fr;
      }
    }
  </style>
</body>
</html>