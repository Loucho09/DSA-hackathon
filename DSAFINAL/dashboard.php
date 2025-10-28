<?php
require_once 'auth.php';
requireAuth();
require_once __DIR__ . '/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$user = [
    'name'       => 'User',
    'email'      => $_SESSION['user_email'] ?? 'user@example.com',
    'join_date'  => '2024-01-15',
    'membership' => 'Premium',
    'image'      => ''
];

if ($stmt = $conn->prepare('SELECT full_name, email, created_at, membership_level, profile_image FROM users WHERE id = ? LIMIT 1')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($fullName, $email, $createdAt, $membershipLevel, $profileImage);
    if ($stmt->fetch()) {
        $user['name'] = $fullName ?: $user['name'];
        $user['email'] = $email ?: $user['email'];
        $user['join_date'] = $createdAt ? date('F j, Y', strtotime($createdAt)) : $user['join_date'];
        $user['membership'] = $membershipLevel ?: 'Standard';
        $user['image'] = $profileImage ?: '';
    }
    $stmt->close();
}

// Fetch dashboard stats
$stats = [
    'total_orders' => 0,
    'wishlist_items' => 0,
    'routine_progress' => 0,
    'skin_analysis_count' => 0
];

// Total orders
if ($stmt = $conn->prepare('SELECT COUNT(*) FROM orders WHERE user_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($totalOrders);
    $stmt->fetch();
    $stats['total_orders'] = $totalOrders;
    $stmt->close();
}

// Wishlist items
if ($stmt = $conn->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($wishlistItems);
    $stmt->fetch();
    $stats['wishlist_items'] = $wishlistItems;
    $stmt->close();
}

// Skin analysis count
if ($stmt = $conn->prepare('SELECT COUNT(*) FROM skin_analysis WHERE user_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($skinAnalysisCount);
    $stmt->fetch();
    $stats['skin_analysis_count'] = $skinAnalysisCount;
    $stmt->close();
}

// Recent orders
$recentOrders = [];
if ($stmt = $conn->prepare('SELECT id, order_number, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recentOrders[] = $row;
    }
    $stmt->close();
}

// Routine progress (default for new users)
$routineProgress = [
    'has_routine' => false,
    'progress' => 0,
    'steps_completed' => 0,
    'total_steps' => 5
];

// Check if user has a routine
if ($stmt = $conn->prepare('SELECT progress, steps_completed FROM skincare_routines WHERE user_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($progress, $stepsCompleted);
    if ($stmt->fetch()) {
        $routineProgress['has_routine'] = true;
        $routineProgress['progress'] = $progress;
        $routineProgress['steps_completed'] = $stepsCompleted;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            <a href="profile.php">Profile</a>
            <a href="skin-analysis.php">Skin Analysis</a>
            <a href="upgrade-membership.php">Upgrade Membership</a>
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
      <!-- Welcome Section -->
      <div class="welcome-section">
        <div class="welcome-left">
          <div class="welcome-text">
            <h1>Welcome back, <?php echo htmlspecialchars($user['name']); ?>! 👋</h1>
            <p>Here's your skincare journey overview</p>
            <div class="member-since">Member since <?php echo $user['join_date']; ?></div>
          </div>
        </div>
        <div class="welcome-right">
          <div class="membership-card">
            <div class="membership-header">
              <span class="badge <?php echo strtolower($user['membership']); ?>">
                <?php echo $user['membership']; ?> Member
              </span>
            </div>
            <?php if ($user['membership'] === 'Standard'): ?>
              <p class="upgrade-text">Unlock premium features</p>
              <a href="upgrade-membership.php" class="btn btn-upgrade">Upgrade Now</a>
            <?php else: ?>
              <p class="upgrade-text">Enjoying premium benefits</p>
              <a href="upgrade-membership.php" class="btn btn-outline-small">Manage Plan</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card gradient-1">
          <div class="stat-content">
            <div class="stat-header">
              <span class="stat-icon">📊</span>
              <h3>Routine Progress</h3>
            </div>
            <div class="stat-value"><?php echo $routineProgress['progress']; ?>%</div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: <?php echo $routineProgress['progress']; ?>%"></div>
            </div>
            <p class="stat-label"><?php echo $routineProgress['steps_completed']; ?> of <?php echo $routineProgress['total_steps']; ?> steps</p>
          </div>
        </div>

        <div class="stat-card gradient-2">
          <div class="stat-content">
            <div class="stat-header">
              <span class="stat-icon">📦</span>
              <h3>Total Orders</h3>
            </div>
            <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
            <p class="stat-label">All-time purchases</p>
          </div>
        </div>

        <div class="stat-card gradient-3">
          <div class="stat-content">
            <div class="stat-header">
              <span class="stat-icon">❤️</span>
              <h3>Wishlist</h3>
            </div>
            <div class="stat-value"><?php echo $stats['wishlist_items']; ?></div>
            <p class="stat-label">Products saved</p>
          </div>
        </div>

        <div class="stat-card gradient-4">
          <div class="stat-content">
            <div class="stat-header">
              <span class="stat-icon">🔍</span>
              <h3>Analysis</h3>
            </div>
            <div class="stat-value"><?php echo $stats['skin_analysis_count']; ?></div>
            <p class="stat-label">Skin scans completed</p>
          </div>
        </div>
      </div>

      <div class="dashboard-content">
        <!-- Recent Orders -->
        <div class="content-section">
          <div class="section-header">
            <h2>Recent Orders</h2>
            <a href="shopall.php" class="view-all">View All →</a>
          </div>
          <?php if (empty($recentOrders)): ?>
            <div class="empty-state">
              <div class="empty-icon">📦</div>
              <h3>No orders yet</h3>
              <p>Start your skincare journey with our premium products</p>
              <a href="shopall.php" class="btn btn-primary">Start Shopping</a>
            </div>
          <?php else: ?>
            <div class="orders-list">
              <?php foreach ($recentOrders as $order): ?>
                <div class="order-item">
                  <div class="order-left">
                    <div class="order-icon">📦</div>
                    <div class="order-info">
                      <h4>Order #<?php echo $order['order_number']; ?></h4>
                      <p class="order-details">
                        <span class="order-amount">$<?php echo number_format($order['total_amount'], 2); ?></span>
                        <span class="order-separator">•</span>
                        <span class="order-date"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></span>
                      </p>
                    </div>
                  </div>
                  <div class="order-status <?php echo strtolower($order['status']); ?>">
                    <?php echo $order['status']; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Skincare Routine -->
        <div class="content-section">
          <div class="section-header">
            <h2>Skincare Routine</h2>
            <?php if ($routineProgress['has_routine']): ?>
              <a href="profile.php?tab=routine" class="view-all">Edit →</a>
            <?php else: ?>
              <a href="skin-analysis.php" class="view-all">Create →</a>
            <?php endif; ?>
          </div>
          <?php if (!$routineProgress['has_routine']): ?>
            <div class="empty-state">
              <div class="empty-icon">🌟</div>
              <h3>No routine set up</h3>
              <p>Complete a skin analysis to get your personalized skincare routine</p>
              <a href="skin-analysis.php" class="btn btn-primary">Start Analysis</a>
            </div>
          <?php else: ?>
            <div class="routine-progress">
              <div class="routine-steps">
                <div class="step <?php echo $routineProgress['progress'] >= 20 ? 'completed' : ''; ?>">
                  <div class="step-circle">
                    <span class="step-number">1</span>
                    <?php if ($routineProgress['progress'] >= 20): ?>
                      <span class="step-check">✓</span>
                    <?php endif; ?>
                  </div>
                  <span class="step-label">Cleansing</span>
                </div>
                <div class="step-connector <?php echo $routineProgress['progress'] >= 40 ? 'active' : ''; ?>"></div>
                <div class="step <?php echo $routineProgress['progress'] >= 40 ? 'completed' : ''; ?>">
                  <div class="step-circle">
                    <span class="step-number">2</span>
                    <?php if ($routineProgress['progress'] >= 40): ?>
                      <span class="step-check">✓</span>
                    <?php endif; ?>
                  </div>
                  <span class="step-label">Toning</span>
                </div>
                <div class="step-connector <?php echo $routineProgress['progress'] >= 60 ? 'active' : ''; ?>"></div>
                <div class="step <?php echo $routineProgress['progress'] >= 60 ? 'completed' : ''; ?>">
                  <div class="step-circle">
                    <span class="step-number">3</span>
                    <?php if ($routineProgress['progress'] >= 60): ?>
                      <span class="step-check">✓</span>
                    <?php endif; ?>
                  </div>
                  <span class="step-label">Treatment</span>
                </div>
                <div class="step-connector <?php echo $routineProgress['progress'] >= 80 ? 'active' : ''; ?>"></div>
                <div class="step <?php echo $routineProgress['progress'] >= 80 ? 'completed' : ''; ?>">
                  <div class="step-circle">
                    <span class="step-number">4</span>
                    <?php if ($routineProgress['progress'] >= 80): ?>
                      <span class="step-check">✓</span>
                    <?php endif; ?>
                  </div>
                  <span class="step-label">Moisturizing</span>
                </div>
                <div class="step-connector <?php echo $routineProgress['progress'] >= 100 ? 'active' : ''; ?>"></div>
                <div class="step <?php echo $routineProgress['progress'] >= 100 ? 'completed' : ''; ?>">
                  <div class="step-circle">
                    <span class="step-number">5</span>
                    <?php if ($routineProgress['progress'] >= 100): ?>
                      <span class="step-check">✓</span>
                    <?php endif; ?>
                  </div>
                  <span class="step-label">Protection</span>
                </div>
              </div>
              <div class="routine-tips">
                <div class="tip-icon">💡</div>
                <div class="tip-content">
                  <h4>Daily Tip</h4>
                  <p>Remember to apply sunscreen every morning, even on cloudy days! UV protection is essential for healthy skin.</p>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="content-section quick-actions-section">
          <h2>Quick Actions</h2>
          <div class="quick-actions">
            <a href="shopall.php" class="action-card">
              <div class="action-icon-wrapper">
                <span class="action-icon">🛍️</span>
              </div>
              <h4>Shop Products</h4>
              <p>Browse our skincare collection</p>
            </a>
            <a href="skin-analysis.php" class="action-card">
              <div class="action-icon-wrapper">
                <span class="action-icon">🔍</span>
              </div>
              <h4>Skin Analysis</h4>
              <p>Get personalized recommendations</p>
            </a>
            <a href="profile.php" class="action-card">
              <div class="action-icon-wrapper">
                <span class="action-icon">👤</span>
              </div>
              <h4>Profile Settings</h4>
              <p>Update your information</p>
            </a>
            <a href="support.php" class="action-card">
              <div class="action-icon-wrapper">
                <span class="action-icon">💬</span>
              </div>
              <h4>Get Support</h4>
              <p>We're here to help</p>
            </a>
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
    // User menu dropdown
    document.addEventListener('DOMContentLoaded', function() {
      const userMenu = document.querySelector('.user-menu');
      const dropdown = document.querySelector('.dropdown');
      
      if (userMenu && dropdown) {
        userMenu.addEventListener('click', function(e) {
          e.stopPropagation();
          dropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
          dropdown.classList.remove('show');
        });
      }

      // Animate stats on load
      const statValues = document.querySelectorAll('.stat-value');
      statValues.forEach(stat => {
        const value = parseInt(stat.textContent);
        let current = 0;
        const increment = value / 30;
        const timer = setInterval(() => {
          current += increment;
          if (current >= value) {
            stat.textContent = value + (stat.textContent.includes('%') ? '%' : '');
            clearInterval(timer);
          } else {
            stat.textContent = Math.floor(current) + (stat.textContent.includes('%') ? '%' : '');
          }
        }, 30);
      });
    });
  </script>

  <style>
    :root {
      --red-side: #8B0000;
      --red-hover: #A52A2A;
      --btn-bg: #8B0000;
      --btn-hover: #A52A2A;
      --text-dark: #1a202c;
      --text-light: #718096;
      --text-muted: #a0aec0;
      --bg-light: #f7fafc;
      --bg-card: #ffffff;
      --border-color: #e2e8f0;
      --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
      --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
      --radius: 12px;
      --radius-sm: 8px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
      padding: 0 24px;
    }

    /* Header */
    .header {
      background: var(--bg-card);
      border-bottom: 1px solid var(--border-color);
      padding: 1rem 0;
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
    }

    .header .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo h1 {
      color: var(--red-side);
      font-size: 1.8rem;
      font-weight: 800;
      letter-spacing: 2px;
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

    .nav a:hover {
      color: var(--red-side);
    }

    .nav a::after {
      content: '';
      position: absolute;
      bottom: -4px;
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

    .user-avatar:hover {
      border-color: var(--red-side);
      transform: scale(1.05);
    }

    .dropdown {
      position: absolute;
      top: calc(100% + 10px);
      right: 0;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-sm);
      box-shadow: var(--shadow-lg);
      padding: 0.5rem 0;
      min-width: 200px;
      display: none;
      z-index: 1000;
    }

    .dropdown.show {
      display: block;
      animation: dropdownFade 0.2s ease;
    }

    @keyframes dropdownFade {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .dropdown a {
      display: block;
      padding: 0.75rem 1.25rem;
      color: var(--text-dark);
      text-decoration: none;
      transition: var(--transition);
      font-size: 0.95rem;
    }

    .dropdown a::after {
      display: none;
    }

    .dropdown a:hover {
      background: var(--bg-light);
      color: var(--red-side);
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 2.5rem 0;
    }

    /* Welcome Section */
    .welcome-section {
      background: linear-gradient(135deg, var(--red-side) 0%, #A52A2A 100%);
      padding: 2.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      margin-bottom: 2.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .welcome-section::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      border-radius: 50%;
      transform: translate(30%, -30%);
    }

    .welcome-left,
    .welcome-right {
      position: relative;
      z-index: 1;
    }

    .welcome-text h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .welcome-text p {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 0.75rem;
    }

    .member-since {
      font-size: 0.9rem;
      opacity: 0.8;
    }

    .membership-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      padding: 1.5rem;
      border-radius: var(--radius-sm);
      border: 1px solid rgba(255, 255, 255, 0.2);
      text-align: center;
      min-width: 220px;
    }

    .membership-header {
      margin-bottom: 1rem;
    }

    .badge {
      padding: 0.5rem 1.25rem;
      border-radius: 20px;
      font-weight: 600;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: inline-block;
    }

    .badge.premium {
      background: linear-gradient(135deg, #FFD700, #FFA500);
      color: #000;
    }

    .badge.elite {
      background: linear-gradient(135deg, #C0C0C0, #808080);
      color: white;
    }

    .badge.standard {
      background: rgba(255, 255, 255, 0.3);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .upgrade-text {
      font-size: 0.9rem;
      margin-bottom: 1rem;
      opacity: 0.9;
    }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .stat-card {
      background: var(--bg-card);
      padding: 0;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      transition: var(--transition);
      overflow: hidden;
      position: relative;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.gradient-1::before {
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.gradient-2::before {
      background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.gradient-3::before {
      background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card.gradient-4::before {
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
    }

    .stat-content {
      padding: 1.5rem;
    }

    .stat-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .stat-icon {
      font-size: 1.5rem;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--bg-light);
      border-radius: var(--radius-sm);
    }

    .stat-header h3 {
      font-size: 0.9rem;
      color: var(--text-light);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .stat-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      line-height: 1;
    }

    .progress-bar {
      width: 100%;
      height: 8px;
      background: var(--bg-light);
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 0.75rem;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
      border-radius: 4px;
      transition: width 1s ease;
    }

    .stat-label {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin: 0;
    }

    /* Dashboard Content */
    .dashboard-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }

    .content-section {
      background: var(--bg-card);
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .quick-actions-section {
      grid-column: 1 / -1;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--bg-light);
    }

    .section-header h2 {
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .view-all {
      color: var(--red-side);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .view-all:hover {
      color: var(--red-hover);
      transform: translateX(3px);
    }

    /* Empty States */
    .empty-state {
      text-align: center;
      padding: 3rem 2rem;
    }

    .empty-icon {
      font-size: 4rem;
      margin-bottom: 1.5rem;
      opacity: 0.6;
    }

    .empty-state h3 {
      color: var(--text-dark);
      margin-bottom: 0.75rem;
      font-size: 1.3rem;
      font-weight: 600;
    }

    .empty-state p {
      color: var(--text-light);
      margin-bottom: 1.5rem;
      font-size: 1rem;
    }

    /* Orders List */
    .orders-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.25rem;
      background: var(--bg-light);
      border-radius: var(--radius-sm);
      border: 1px solid var(--border-color);
      transition: var(--transition);
    }

    .order-item:hover {
      border-color: var(--red-side);
      transform: translateX(5px);
    }

    .order-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .order-icon {
      font-size: 1.5rem;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: white;
      border-radius: var(--radius-sm);
      border: 1px solid var(--border-color);
    }

    .order-info h4 {
      color: var(--text-dark);
      margin-bottom: 0.25rem;
      font-size: 1rem;
      font-weight: 600;
    }

    .order-details {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
      color: var(--text-light);
    }

    .order-amount {
      font-weight: 600;
      color: var(--text-dark);
    }

    .order-separator {
      color: var(--text-muted);
    }

    .order-status {
      padding: 0.4rem 1rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .order-status.delivered {
      background: #d1fae5;
      color: #065f46;
    }

    .order-status.processing {
      background: #fef3c7;
      color: #92400e;
    }

    .order-status.shipped {
      background: #dbeafe;
      color: #1e40af;
    }

    .order-status.pending {
      background: #e5e7eb;
      color: #374151;
    }

    /* Routine Progress */
    .routine-steps {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 2rem;
      padding: 1.5rem;
      background: var(--bg-light);
      border-radius: var(--radius-sm);
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
      position: relative;
    }

    .step-circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: white;
      border: 3px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 1rem;
      color: var(--text-light);
      position: relative;
      transition: var(--transition);
    }

    .step.completed .step-circle {
      background: var(--red-side);
      border-color: var(--red-side);
      color: white;
    }

    .step-number {
      display: block;
    }

    .step.completed .step-number {
      display: none;
    }

    .step-check {
      display: none;
      font-size: 1.2rem;
    }

    .step.completed .step-check {
      display: block;
    }

    .step-label {
      font-size: 0.8rem;
      color: var(--text-light);
      font-weight: 500;
      text-align: center;
    }

    .step.completed .step-label {
      color: var(--text-dark);
      font-weight: 600;
    }

    .step-connector {
      flex: 1;
      height: 3px;
      background: var(--border-color);
      margin: 0 0.5rem;
      margin-bottom: 2rem;
      transition: var(--transition);
    }

    .step-connector.active {
      background: var(--red-side);
    }

    .routine-tips {
      background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
      padding: 1.5rem;
      border-radius: var(--radius-sm);
      border-left: 4px solid #667eea;
      display: flex;
      gap: 1rem;
    }

    .tip-icon {
      font-size: 2rem;
      flex-shrink: 0;
    }

    .tip-content h4 {
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .tip-content p {
      color: var(--text-light);
      font-size: 0.95rem;
      line-height: 1.6;
    }

    /* Quick Actions */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
    }

    .action-card {
      background: var(--bg-light);
      padding: 2rem;
      border-radius: var(--radius-sm);
      text-decoration: none;
      text-align: center;
      transition: var(--transition);
      border: 2px solid transparent;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .action-card:hover {
      border-color: var(--red-side);
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
      background: white;
    }

    .action-icon-wrapper {
      width: 70px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: white;
      border-radius: var(--radius-sm);
      margin-bottom: 1rem;
      border: 1px solid var(--border-color);
      transition: var(--transition);
    }

    .action-card:hover .action-icon-wrapper {
      transform: scale(1.1);
      border-color: var(--red-side);
    }

    .action-icon {
      font-size: 2rem;
    }

    .action-card h4 {
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .action-card p {
      color: var(--text-light);
      font-size: 0.9rem;
      margin: 0;
    }

    /* Buttons */
    .btn {
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: var(--radius-sm);
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn-primary {
      background: var(--btn-bg);
      color: white;
    }

    .btn-primary:hover {
      background: var(--btn-hover);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
    }

    .btn-upgrade {
      background: white;
      color: var(--red-side);
      font-weight: 700;
    }

    .btn-upgrade:hover {
      background: #f8f8f8;
      transform: translateY(-2px);
    }

    .btn-outline-small {
      background: transparent;
      color: white;
      border: 2px solid rgba(255, 255, 255, 0.5);
      font-size: 0.85rem;
      padding: 0.5rem 1rem;
    }

    .btn-outline-small:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: white;
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
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2.5rem;
      margin-bottom: 2.5rem;
    }

    .footer-section h3 {
      color: white;
      margin-bottom: 1rem;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1px;
    }

    .footer-section ul {
      list-style: none;
    }

    .footer-section li {
      margin-bottom: 0.6rem;
    }

    .footer-section a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: var(--transition);
      font-size: 0.95rem;
    }

    .footer-section a:hover {
      color: white;
      padding-left: 5px;
    }

    .newsletter {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .newsletter-input {
      padding: 0.75rem 1rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: var(--radius-sm);
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 0.95rem;
    }

    .newsletter-input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .newsletter-input:focus {
      outline: none;
      border-color: rgba(255, 255, 255, 0.5);
      background: rgba(255, 255, 255, 0.15);
    }

    .newsletter-btn {
      padding: 0.75rem 1rem;
      background: var(--red-side);
      color: white;
      border: none;
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-size: 0.95rem;
      font-weight: 600;
      transition: var(--transition);
    }

    .newsletter-btn:hover {
      background: var(--red-hover);
      transform: translateY(-2px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 968px) {
      .dashboard-content {
        grid-template-columns: 1fr;
      }

      .welcome-section {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
      }

      .membership-card {
        width: 100%;
      }

      .routine-steps {
        flex-wrap: wrap;
        gap: 1rem;
      }

      .step-connector {
        display: none;
      }
    }

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

      .welcome-text h1 {
        font-size: 1.5rem;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .stat-value {
        font-size: 2rem;
      }

      .quick-actions {
        grid-template-columns: 1fr;
      }

      .order-left {
        flex-direction: column;
        text-align: center;
      }

      .order-item {
        flex-direction: column;
        gap: 1rem;
      }

      .routine-steps {
        flex-direction: column;
      }

      .step {
        flex-direction: row;
        width: 100%;
        justify-content: flex-start;
        text-align: left;
      }

      .footer-sections {
        grid-template-columns: 1fr;
        text-align: center;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 0 16px;
      }

      .welcome-section {
        padding: 1.5rem;
      }

      .content-section {
        padding: 1.5rem;
      }

      .nav {
        gap: 0.75rem;
      }

      .nav a {
        font-size: 0.9rem;
      }

      .stat-content {
        padding: 1.25rem;
      }

      .section-header h2 {
        font-size: 1.2rem;
      }
    }
  </style>
</body>
</html>