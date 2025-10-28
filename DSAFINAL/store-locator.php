<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DERMALUXE</title>
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
        <h1>Product Size Guide</h1>
        <p class="subtitle">Find the perfect size for your skincare products</p>
      </div>

      <div class="size-guide">
        <!-- Product Sizes Section -->
        <div class="guide-section">
          <h2>Product Container Sizes</h2>
          <div class="sizes-grid">
            <div class="size-card">
              <div class="size-badge">30ml</div>
              <h3>Travel Size</h3>
              <p>Perfect for trying new products or travel</p>
              <ul>
                <li>Lasts 2-4 weeks</li>
                <li>Ideal for sampling</li>
                <li>TSA compliant</li>
              </ul>
            </div>

            <div class="size-card">
              <div class="size-badge">50ml</div>
              <h3>Standard Size</h3>
              <p>Our most popular size for daily use</p>
              <ul>
                <li>Lasts 6-8 weeks</li>
                <li>Best value</li>
                <li>Daily routine</li>
              </ul>
            </div>

            <div class="size-card">
              <div class="size-badge">100ml</div>
              <h3>Family Size</h3>
              <p>Great for sharing or extended use</p>
              <ul>
                <li>Lasts 3-4 months</li>
                <li>Economical choice</li>
                <li>Multiple users</li>
              </ul>
            </div>

            <div class="size-card">
              <div class="size-badge">200ml</div>
              <h3>Professional Size</h3>
              <p>For spa professionals or heavy users</p>
              <ul>
                <li>Lasts 6+ months</li>
                <li>Professional use</li>
                <li>Maximum savings</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Usage Duration Guide -->
        <div class="guide-section">
          <h2>Expected Usage Duration</h2>
          <div class="usage-table">
            <table>
              <thead>
                <tr>
                  <th>Product Type</th>
                  <th>30ml</th>
                  <th>50ml</th>
                  <th>100ml</th>
                  <th>200ml</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Cleansers</td>
                  <td>3-4 weeks</td>
                  <td>6-8 weeks</td>
                  <td>3-4 months</td>
                  <td>6-8 months</td>
                </tr>
                <tr>
                  <td>Serums</td>
                  <td>4-6 weeks</td>
                  <td>8-10 weeks</td>
                  <td>4-5 months</td>
                  <td>8-10 months</td>
                </tr>
                <tr>
                  <td>Moisturizers</td>
                  <td>2-3 weeks</td>
                  <td>4-6 weeks</td>
                  <td>2-3 months</td>
                  <td>4-6 months</td>
                </tr>
                <tr>
                  <td>Sunscreen</td>
                  <td>2-3 weeks</td>
                  <td>4-5 weeks</td>
                  <td>2-3 months</td>
                  <td>4-5 months</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Measurement Guide -->
        <div class="guide-section">
          <h2>Measurement Conversion</h2>
          <div class="conversion-grid">
            <div class="conversion-card">
              <h3>Volume Conversion</h3>
              <div class="conversion-list">
                <div class="conversion-item">
                  <span>1ml</span>
                  <span>=</span>
                  <span>~20 drops</span>
                </div>
                <div class="conversion-item">
                  <span>5ml</span>
                  <span>=</span>
                  <span>1 teaspoon</span>
                </div>
                <div class="conversion-item">
                  <span>15ml</span>
                  <span>=</span>
                  <span>1 tablespoon</span>
                </div>
                <div class="conversion-item">
                  <span>30ml</span>
                  <span>=</span>
                  <span>1 fluid ounce</span>
                </div>
              </div>
            </div>

            <div class="conversion-card">
              <h3>Application Guide</h3>
              <div class="application-tips">
                <div class="tip">
                  <strong>Cleanser:</strong> Almond-sized amount
                </div>
                <div class="tip">
                  <strong>Serum:</strong> 2-3 drops
                </div>
                <div class="tip">
                  <strong>Moisturizer:</strong> Pea-sized amount
                </div>
                <div class="tip">
                  <strong>Sunscreen:</strong> Quarter-sized amount
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tips Section -->
        <div class="guide-section">
          <h2>Usage Tips</h2>
          <div class="tips-grid">
            <div class="tip-card">
              <h3>💧 Proper Storage</h3>
              <p>Keep products in a cool, dry place away from direct sunlight to maintain efficacy.</p>
            </div>
            <div class="tip-card">
              <h3>🧴 Hygiene</h3>
              <p>Always use clean hands or applicators to prevent contamination.</p>
            </div>
            <div class="tip-card">
              <h3>⏰ Shelf Life</h3>
              <p>Most products maintain potency for 6-12 months after opening.</p>
            </div>
            <div class="tip-card">
              <h3>📝 Patch Testing</h3>
              <p>Always test new products on a small area before full application.</p>
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

    /* Guide Sections */
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
      text-align: center;
    }

    /* Sizes Grid */
    .sizes-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .size-card {
      background: var(--bg-light);
      padding: 2rem;
      border-radius: var(--radius);
      text-align: center;
      border: 2px solid transparent;
      transition: var(--transition);
    }

    .size-card:hover {
      border-color: var(--btn-bg);
      transform: translateY(-5px);
    }

    .size-badge {
      background: var(--red-side);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-weight: 700;
      font-size: 1.2rem;
      display: inline-block;
      margin-bottom: 1rem;
    }

    .size-card h3 {
      color: var(--text-dark);
      margin-bottom: 1rem;
      font-size: 1.3rem;
    }

    .size-card p {
      color: var(--text-light);
      margin-bottom: 1rem;
    }

    .size-card ul {
      list-style: none;
      padding-left: 0;
    }

    .size-card li {
      padding: 0.25rem 0;
      color: var(--text-light);
      position: relative;
    }

    .size-card li:before {
      content: "✓";
      color: var(--btn-bg);
      font-weight: bold;
      margin-right: 0.5rem;
    }

    /* Usage Table */
    .usage-table {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
    }

    th, td {
      padding: 1rem;
      text-align: center;
      border: 1px solid var(--border-color);
    }

    th {
      background: var(--red-side);
      color: white;
      font-weight: 600;
    }

    tbody tr:nth-child(even) {
      background: var(--bg-light);
    }

    tbody tr:hover {
      background: #e9ecef;
    }

    /* Conversion Grid */
    .conversion-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }

    .conversion-card {
      background: var(--bg-light);
      padding: 2rem;
      border-radius: var(--radius);
      border-left: 4px solid var(--btn-bg);
    }

    .conversion-card h3 {
      color: var(--text-dark);
      margin-bottom: 1.5rem;
      font-size: 1.3rem;
    }

    .conversion-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .conversion-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem;
      background: white;
      border-radius: var(--radius);
    }

    .conversion-item span:first-child {
      font-weight: 600;
      color: var(--red-side);
    }

    .conversion-item span:last-child {
      color: var(--text-light);
    }

    .application-tips {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .tip {
      padding: 0.75rem;
      background: white;
      border-radius: var(--radius);
      border-left: 3px solid var(--btn-bg);
    }

    .tip strong {
      color: var(--text-dark);
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
      text-align: center;
      transition: var(--transition);
    }

    .tip-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }

    .tip-card h3 {
      color: var(--text-dark);
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }

    .tip-card p {
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

      .guide-section {
        padding: 1.5rem;
      }

      .sizes-grid {
        grid-template-columns: 1fr;
      }

      .conversion-grid {
        grid-template-columns: 1fr;
      }

      .tips-grid {
        grid-template-columns: 1fr;
      }

      .footer-sections {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .page-header h1 {
        font-size: 2rem;
      }

      th, td {
        padding: 0.75rem 0.5rem;
        font-size: 0.9rem;
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

      .size-card,
      .conversion-card {
        padding: 1.5rem;
      }

      .usage-table {
        font-size: 0.8rem;
      }
    }
  </style>
</body>
</html>