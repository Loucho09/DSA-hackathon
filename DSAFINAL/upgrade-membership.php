<?php
require_once 'auth.php';
requireAuth();
require_once __DIR__ . '/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details and current membership
$user = [
    'name' => 'User',
    'current_membership' => 'Standard',
    'image' => ''
];

if ($stmt = $conn->prepare('SELECT full_name, membership_level, profile_image FROM users WHERE id = ? LIMIT 1')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($fullName, $membershipLevel, $profileImage);
    if ($stmt->fetch()) {
        $user['name'] = $fullName ?: $user['name'];
        $user['current_membership'] = $membershipLevel ?: 'Standard';
        $user['image'] = $profileImage ?: '';
    }
    $stmt->close();
}

// Handle membership upgrade
$upgrade_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upgrade_membership'])) {
    $new_membership = $_POST['membership_plan'];
    
    if ($stmt = $conn->prepare('UPDATE users SET membership_level = ? WHERE id = ?')) {
        $stmt->bind_param('si', $new_membership, $user_id);
        if ($stmt->execute()) {
            $upgrade_message = "Successfully upgraded to $new_membership membership!";
            $user['current_membership'] = $new_membership;
            
            // In real app, you would process payment here
            if ($new_membership === 'Premium') {
                // Process premium payment
            } elseif ($new_membership === 'Elite') {
                // Process elite payment
            }
        } else {
            $upgrade_message = "Error upgrading membership. Please try again.";
        }
        $stmt->close();
    }
}

$membership_plans = [
    'Standard' => [
        'price' => 0,
        'period' => 'Free',
        'features' => [
            'Basic product recommendations',
            'Standard skin analysis',
            'Email support',
            'Access to basic articles'
        ],
        'limitations' => [
            'Limited product recommendations',
            'Basic analysis only',
            'Standard support response time'
        ]
    ],
    'Premium' => [
        'price' => 19.99,
        'period' => 'per month',
        'features' => [
            'Personalized product matching',
            'Advanced skin analysis',
            'Priority email support',
            'Exclusive video content',
            'Early access to new products',
            'Monthly skincare consultation'
        ],
        'popular' => true
    ],
    'Elite' => [
        'price' => 49.99,
        'period' => 'per month',
        'features' => [
            'All Premium features',
            '1-on-1 dermatologist consultation',
            'Customized product formulations',
            '24/7 priority support',
            'Quarterly product packages',
            'VIP event invitations',
            'Unlimited skin analysis'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Upgrade Membership</title>
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
        <a href="store_locator.php">Stores</a>
        <a href="faqs.php">FAQs</a>
        <div class="user-menu">
          <img src="<?php echo $user['image'] ?: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGMEYwRjAiLz4KPGNpcmNsZSBjeD0iMjAiIGN5PSIxNiIgcj0iNSIgZmlsbD0iI0NFQ0VDRSIvPjxwYXRoIGQ9Ik0yOCAzMGMwLTQuNDE4LTQuNDU4LTgtMTAtOFM4IDI1LjU4MiA4IDMwIiBmaWxsPSIjQ0VDRUNFIi8+Cjwvc3ZnPgo='; ?>" 
               alt="Profile" class="user-avatar">
          <div class="dropdown">
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
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
      <div class="upgrade-header">
        <h1>Upgrade Your Membership</h1>
        <p>Unlock exclusive benefits and personalized skincare experiences</p>
        <div class="current-plan">
          Your current plan: <span class="plan-badge <?php echo strtolower($user['current_membership']); ?>"><?php echo $user['current_membership']; ?></span>
        </div>
      </div>

      <?php if ($upgrade_message): ?>
        <div class="message success"><?php echo $upgrade_message; ?></div>
      <?php endif; ?>

      <!-- Membership Plans -->
      <div class="plans-container">
        <?php foreach ($membership_plans as $plan_name => $plan): ?>
          <div class="plan-card <?php echo isset($plan['popular']) ? 'popular' : ''; ?> <?php echo $user['current_membership'] === $plan_name ? 'current' : ''; ?>">
            <?php if (isset($plan['popular'])): ?>
              <div class="popular-badge">Most Popular</div>
            <?php endif; ?>
            
            <div class="plan-header">
              <h3><?php echo $plan_name; ?></h3>
              <div class="plan-price">
                <?php if ($plan_name === 'Standard'): ?>
                  <span class="price-amount">Free</span>
                <?php else: ?>
                  <span class="price-amount">$<?php echo number_format($plan['price'], 2); ?></span>
                  <span class="price-period"><?php echo $plan['period']; ?></span>
                <?php endif; ?>
              </div>
            </div>

            <div class="plan-features">
              <h4>What's included:</h4>
              <ul>
                <?php foreach ($plan['features'] as $feature): ?>
                  <li class="feature-included">✓ <?php echo $feature; ?></li>
                <?php endforeach; ?>
              </ul>
              
              <?php if (isset($plan['limitations'])): ?>
                <div class="limitations">
                  <h5>Limitations:</h5>
                  <ul>
                    <?php foreach ($plan['limitations'] as $limitation): ?>
                      <li class="feature-excluded">✗ <?php echo $limitation; ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>
            </div>

            <div class="plan-actions">
              <?php if ($user['current_membership'] === $plan_name): ?>
                <button class="btn btn-outline" disabled>Current Plan</button>
              <?php elseif ($plan_name === 'Standard'): ?>
                <a href="dashboard.php" class="btn btn-outline">Back to Dashboard</a>
              <?php else: ?>
                <form method="POST" class="upgrade-form">
                  <input type="hidden" name="membership_plan" value="<?php echo $plan_name; ?>">
                  <button type="submit" name="upgrade_membership" class="btn btn-primary">
                    <?php echo $user['current_membership'] === 'Standard' ? 'Upgrade Now' : 'Switch to ' . $plan_name; ?>
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Feature Comparison -->
      <div class="comparison-section">
        <h2>Plan Comparison</h2>
        <div class="comparison-table">
          <table>
            <thead>
              <tr>
                <th>Feature</th>
                <th>Standard</th>
                <th>Premium</th>
                <th>Elite</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Personalized Product Matching</td>
                <td>Basic</td>
                <td>Advanced</td>
                <td>Custom</td>
              </tr>
              <tr>
                <td>Skin Analysis</td>
                <td>Standard</td>
                <td>Advanced</td>
                <td>Unlimited + Dermatologist</td>
              </tr>
              <tr>
                <td>Support Response Time</td>
                <td>48 hours</td>
                <td>24 hours</td>
                <td>2 hours</td>
              </tr>
              <tr>
                <td>Exclusive Content</td>
                <td>Basic Articles</td>
                <td>Video Content</td>
                <td>All Content + VIP Events</td>
              </tr>
              <tr>
                <td>Product Consultation</td>
                <td>-</td>
                <td>Monthly</td>
                <td>Weekly + Custom Formulations</td>
              </tr>
              <tr>
                <td>Product Packages</td>
                <td>-</td>
                <td>-</td>
                <td>Quarterly</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Testimonials -->
      <div class="testimonials-section">
        <h2>What Our Members Say</h2>
        <div class="testimonials-grid">
          <div class="testimonial">
            <div class="testimonial-content">
              "The Premium membership transformed my skincare routine. The personalized recommendations are spot on!"
            </div>
            <div class="testimonial-author">
              <strong>Sarah M.</strong>
              <span>Premium Member</span>
            </div>
          </div>
          <div class="testimonial">
            <div class="testimonial-content">
              "As an Elite member, the dermatologist consultations alone are worth the price. My skin has never been better."
            </div>
            <div class="testimonial-author">
              <strong>James L.</strong>
              <span>Elite Member</span>
            </div>
          </div>
          <div class="testimonial">
            <div class="testimonial-content">
              "Upgrading to Premium was the best decision. The early access to new products is incredible!"
            </div>
            <div class="testimonial-author">
              <strong>Maria K.</strong>
              <span>Premium Member</span>
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Section -->
      <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-grid">
          <div class="faq-item">
            <h3>Can I cancel my membership anytime?</h3>
            <p>Yes, you can cancel your membership at any time. Your benefits will remain active until the end of your billing cycle.</p>
          </div>
          <div class="faq-item">
            <h3>Is there a contract or commitment?</h3>
            <p>No, all memberships are month-to-month with no long-term commitment required.</p>
          </div>
          <div class="faq-item">
            <h3>Can I switch between plans?</h3>
            <p>Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
          </div>
          <div class="faq-item">
            <h3>Do you offer refunds?</h3>
            <p>We offer a 14-day money-back guarantee for new memberships if you're not satisfied.</p>
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

  <style>
    /* CSS Reset & Variables */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --red-side: #8B0000;
      --btn-bg: #B22222;
      --btn-hover: #DC143C;
      --text-dark: #2c3e50;
      --text-light: #6c757d;
      --bg-light: #f8f9fa;
      --border-color: #dee2e6;
      --white: #ffffff;
      --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      --radius: 8px;
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      line-height: 1.6;
      color: var(--text-dark);
      background: var(--bg-light);
    }

    /* Container */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    /* Header Styles */
    .header {
      background: var(--white);
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 100;
      border-bottom: 3px solid var(--red-side);
    }

    .header .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
    }

    .logo h1 {
      color: var(--red-side);
      font-size: 1.8rem;
      font-weight: 700;
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
      padding: 0.5rem 0;
      border-bottom: 2px solid transparent;
    }

    .nav a:hover {
      color: var(--red-side);
      border-bottom-color: var(--red-side);
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

    .user-avatar:hover {
      border-color: var(--red-side);
    }

    .dropdown {
      display: none;
      position: absolute;
      top: 50px;
      right: 0;
      background: var(--white);
      box-shadow: var(--shadow);
      border-radius: var(--radius);
      min-width: 200px;
      padding: 0.5rem 0;
      border: 1px solid var(--border-color);
    }

    .user-menu:hover .dropdown {
      display: block;
    }

    .dropdown a {
      display: block;
      padding: 0.75rem 1.5rem;
      color: var(--text-dark);
      text-decoration: none;
      transition: var(--transition);
      border-bottom: none;
    }

    .dropdown a:hover {
      background: var(--bg-light);
      color: var(--red-side);
    }

    /* Main Content */
    .main {
      padding: 3rem 0;
      min-height: calc(100vh - 300px);
    }

    /* Button Styles */
    .btn {
      padding: 0.75rem 2rem;
      border: none;
      border-radius: var(--radius);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      display: inline-block;
      text-align: center;
      font-size: 1rem;
    }

    .btn-primary {
      background: var(--btn-bg);
      color: var(--white);
      width: 100%;
    }

    .btn-primary:hover {
      background: var(--btn-hover);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(178, 34, 34, 0.3);
    }

    .btn-outline {
      background: transparent;
      color: var(--text-light);
      border: 2px solid var(--border-color);
      width: 100%;
    }

    .btn-outline:hover:not(:disabled) {
      background: var(--bg-light);
      border-color: var(--red-side);
      color: var(--red-side);
    }

    .btn-outline:disabled {
      cursor: not-allowed;
      opacity: 0.6;
    }

    /* Message Styles */
    .message {
      padding: 1rem 1.5rem;
      border-radius: var(--radius);
      margin-bottom: 2rem;
      font-weight: 500;
    }

    .message.success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    /* Upgrade Membership Specific Styles */
    .upgrade-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .upgrade-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--text-dark);
    }

    .upgrade-header p {
      font-size: 1.1rem;
      color: var(--text-light);
      margin-bottom: 1.5rem;
    }

    .current-plan {
      font-size: 1.1rem;
      color: var(--text-dark);
    }

    .plan-badge {
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
    }

    .plan-badge.standard {
      background: var(--bg-light);
      color: var(--text-light);
      border: 1px solid var(--border-color);
    }

    .plan-badge.premium {
      background: linear-gradient(135deg, #FFD700, #FFA500);
      color: #000;
    }

    .plan-badge.elite {
      background: linear-gradient(135deg, #C0C0C0, #808080);
      color: white;
    }

    .plans-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-bottom: 4rem;
    }

    .plan-card {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 2px solid var(--border-color);
      position: relative;
      transition: var(--transition);
    }

    .plan-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .plan-card.popular {
      border-color: var(--btn-bg);
      transform: scale(1.05);
    }

    .plan-card.current {
      border-color: var(--red-side);
      background: linear-gradient(to bottom, #fff 0%, #fff9f9 100%);
    }

    .popular-badge {
      position: absolute;
      top: -10px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--btn-bg);
      color: white;
      padding: 0.5rem 1.5rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .plan-header {
      text-align: center;
      margin-bottom: 2rem;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid var(--border-color);
    }

    .plan-header h3 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--text-dark);
    }

    .plan-price {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .price-amount {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--text-dark);
    }

    .price-period {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .plan-features {
      margin-bottom: 2rem;
    }

    .plan-features h4 {
      color: var(--text-dark);
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }

    .plan-features ul {
      list-style: none;
      margin-bottom: 1.5rem;
    }

    .plan-features li {
      padding: 0.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .feature-included {
      color: var(--text-dark);
    }

    .feature-excluded {
      color: var(--text-light);
      text-decoration: line-through;
    }

    .limitations {
      background: var(--bg-light);
      padding: 1rem;
      border-radius: var(--radius);
      border-left: 3px solid var(--red-side);
    }

    .limitations h5 {
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    .plan-actions {
      text-align: center;
    }

    .upgrade-form {
      margin: 0;
    }

    /* Comparison Table */
    .comparison-section {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      margin-bottom: 3rem;
    }

    .comparison-section h2 {
      text-align: center;
      color: var(--red-side);
      margin-bottom: 2rem;
      font-size: 1.8rem;
      font-weight: 600;
    }

    .comparison-table {
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

    th:first-child {
      background: var(--text-dark);
      text-align: left;
    }

    tbody tr:nth-child(even) {
      background: var(--bg-light);
    }

    tbody tr:hover {
      background: #e9ecef;
    }

    tbody td:first-child {
      text-align: left;
      font-weight: 500;
    }

    /* Testimonials */
    .testimonials-section {
      text-align: center;
      margin-bottom: 3rem;
    }

    .testimonials-section h2 {
      color: var(--red-side);
      margin-bottom: 2rem;
      font-size: 1.8rem;
      font-weight: 600;
    }

    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }

    .testimonial {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      transition: var(--transition);
    }

    .testimonial:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .testimonial-content {
      font-style: italic;
      color: var(--text-dark);
      margin-bottom: 1.5rem;
      line-height: 1.6;
      position: relative;
    }

    .testimonial-content::before {
      content: '"';
      font-size: 3rem;
      color: var(--red-side);
      opacity: 0.2;
      position: absolute;
      top: -20px;
      left: -10px;
    }

    .testimonial-author strong {
      color: var(--text-dark);
      display: block;
      margin-bottom: 0.25rem;
    }

    .testimonial-author span {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    /* FAQ Section */
    .faq-section {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .faq-section h2 {
      text-align: center;
      color: var(--red-side);
      margin-bottom: 2rem;
      font-size: 1.8rem;
      font-weight: 600;
    }

    .faq-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }

    .faq-item {
      background: var(--bg-light);
      padding: 1.5rem;
      border-radius: var(--radius);
      border-left: 4px solid var(--btn-bg);
      transition: var(--transition);
    }

    .faq-item:hover {
      transform: translateX(5px);
      box-shadow: var(--shadow);
    }

    .faq-item h3 {
      color: var(--text-dark);
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }

    .faq-item p {
      color: var(--text-light);
      line-height: 1.5;
    }

    /* Footer Styles */
    .footer {
      background: var(--text-dark);
      color: var(--white);
      padding: 3rem 0 1rem;
      margin-top: 4rem;
    }

    .footer-sections {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 3rem;
      margin-bottom: 2rem;
    }

    .footer-section h3 {
      font-size: 1rem;
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

    .footer-section ul li a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: var(--transition);
    }

    .footer-section ul li a:hover {
      color: var(--white);
      padding-left: 5px;
    }

    .newsletter {
      display: flex;
      gap: 0.5rem;
    }

    .newsletter-input {
      flex: 1;
      padding: 0.75rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: var(--radius);
      background: rgba(255, 255, 255, 0.1);
      color: var(--white);
      font-size: 0.9rem;
    }

    .newsletter-input::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    .newsletter-btn {
      padding: 0.75rem 1.5rem;
      background: var(--btn-bg);
      color: var(--white);
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
      padding-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.6);
    }

    /* Responsive Design */
    @media (max-width: 968px) {
      .header .container {
        flex-direction: column;
        gap: 1rem;
      }

      .nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }

      .upgrade-header h1 {
        font-size: 2rem;
      }

      .plans-container {
        grid-template-columns: 1fr;
      }

      .plan-card.popular {
        transform: scale(1);
      }

      .comparison-table {
        font-size: 0.9rem;
      }

      th, td {
        padding: 0.5rem;
      }
    }

    @media (max-width: 640px) {
      .container {
        padding: 0 1rem;
      }

      .upgrade-header h1 {
        font-size: 1.5rem;
      }

      .upgrade-header p {
        font-size: 1rem;
      }

      .plan-card {
        padding: 1.5rem;
      }

      .price-amount {
        font-size: 2rem;
      }

      .testimonials-grid,
      .faq-grid {
        grid-template-columns: 1fr;
      }

      .footer-sections {
        grid-template-columns: 1fr;
        gap: 2rem;
      }

      .newsletter {
        flex-direction: column;
      }

      .newsletter-btn {
        width: 100%;
      }
    }
  </style>
</body>
</html>