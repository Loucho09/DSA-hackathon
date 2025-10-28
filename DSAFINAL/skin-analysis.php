<?php
require_once 'auth.php';
requireAuth();
require_once __DIR__ . '/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details for header
$user = [
    'name' => 'User',
    'image' => ''
];

if ($stmt = $conn->prepare('SELECT full_name, profile_image FROM users WHERE id = ? LIMIT 1')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($fullName, $profileImage);
    if ($stmt->fetch()) {
        $user['name'] = $fullName ?: $user['name'];
        $user['image'] = $profileImage ?: '';
    }
    $stmt->close();
}

// Handle skin analysis submission
$analysis_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_analysis'])) {
    $skin_type = $_POST['skin_type'];
    $concerns = $_POST['concerns'] ?? [];
    $sensitivity = $_POST['sensitivity'];
    $lifestyle = $_POST['lifestyle'] ?? [];
    $goals = $_POST['goals'] ?? [];
    
    // Calculate skin score and recommendations
    $skin_score = rand(65, 95); // In real app, this would be calculated
    
    // Generate recommendations based on inputs
    $recommendations = generateRecommendations($skin_type, $concerns, $sensitivity, $lifestyle, $goals);
    
    // Save to database
    $concerns_json = json_encode($concerns);
    $lifestyle_json = json_encode($lifestyle);
    $goals_json = json_encode($goals);
    $recommendations_json = json_encode($recommendations);
    
    if ($stmt = $conn->prepare('INSERT INTO skin_analysis (user_id, skin_type, concerns, sensitivity, lifestyle, goals, skin_score, recommendations) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')) {
        $stmt->bind_param('isssssis', $user_id, $skin_type, $concerns_json, $sensitivity, $lifestyle_json, $goals_json, $skin_score, $recommendations_json);
        $stmt->execute();
        $analysis_id = $stmt->insert_id;
        $stmt->close();
        
        $analysis_result = [
            'id' => $analysis_id,
            'skin_score' => $skin_score,
            'recommendations' => $recommendations,
            'skin_type' => $skin_type,
            'concerns' => $concerns
        ];
    }
}

function generateRecommendations($skin_type, $concerns, $sensitivity, $lifestyle, $goals) {
    $recommendations = [
        'routine' => [],
        'products' => [],
        'tips' => []
    ];
    
    // Basic routine based on skin type
    switch ($skin_type) {
        case 'Dry':
            $recommendations['routine'] = ['Gentle Cream Cleanser', 'Hydrating Toner', 'Rich Moisturizer', 'Facial Oil'];
            $recommendations['tips'][] = 'Use lukewarm water for cleansing to avoid stripping natural oils';
            break;
        case 'Oily':
            $recommendations['routine'] = ['Foaming Cleanser', 'Balancing Toner', 'Oil-Free Moisturizer', 'Clay Mask 2x weekly'];
            $recommendations['tips'][] = 'Don\'t skip moisturizer - it helps balance oil production';
            break;
        case 'Combination':
            $recommendations['routine'] = ['Gel Cleanser', 'Hydrating Toner', 'Lightweight Moisturizer', 'Spot treatment for oily areas'];
            $recommendations['tips'][] = 'Apply different products to different areas of your face';
            break;
        case 'Sensitive':
            $recommendations['routine'] = ['Milk Cleanser', 'Soothing Toner', 'Barrier Repair Cream', 'Mineral Sunscreen'];
            $recommendations['tips'][] = 'Always patch test new products before full application';
            break;
        default:
            $recommendations['routine'] = ['Balancing Cleanser', 'Hydrating Toner', 'Daily Moisturizer', 'Broad Spectrum SPF'];
    }
    
    // Product recommendations based on concerns
    if (in_array('Aging', $concerns)) {
        $recommendations['products'][] = 'Retinol Serum';
        $recommendations['products'][] = 'Peptide Cream';
        $recommendations['tips'][] = 'Incorporate retinol slowly, starting with 1-2 times per week';
    }
    
    if (in_array('Acne', $concerns)) {
        $recommendations['products'][] = 'Salicylic Acid Cleanser';
        $recommendations['products'][] = 'Benzoyl Peroxide Spot Treatment';
        $recommendations['tips'][] = 'Be consistent with your routine - results take 4-6 weeks';
    }
    
    if (in_array('Dark Spots', $concerns)) {
        $recommendations['products'][] = 'Vitamin C Serum';
        $recommendations['products'][] = 'Niacinamide Treatment';
        $recommendations['tips'][] = 'Wear SPF daily to prevent further hyperpigmentation';
    }
    
    if (in_array('Dryness', $concerns)) {
        $recommendations['products'][] = 'Hyaluronic Acid Serum';
        $recommendations['products'][] = 'Ceramide Moisturizer';
    }
    
    // Lifestyle adjustments
    if (in_array('Stress', $lifestyle)) {
        $recommendations['tips'][] = 'Practice stress management techniques as stress can affect skin health';
    }
    
    if (in_array('Poor Sleep', $lifestyle)) {
        $recommendations['tips'][] = 'Aim for 7-9 hours of quality sleep nightly for skin regeneration';
    }
    
    return $recommendations;
}

$skin_types = ['Normal', 'Dry', 'Oily', 'Combination', 'Sensitive'];
$concerns_list = ['Acne', 'Aging', 'Dark Spots', 'Dryness', 'Redness', 'Sensitivity', 'Oiliness', 'Large Pores', 'Dullness'];
$sensitivity_levels = ['Not Sensitive', 'Slightly Sensitive', 'Moderately Sensitive', 'Very Sensitive'];
$lifestyle_factors = ['Smoking', 'Alcohol', 'Stress', 'Poor Sleep', 'Unhealthy Diet', 'Sedentary Lifestyle'];
$skin_goals = ['Anti-Aging', 'Brightening', 'Hydration', 'Acne Control', 'Even Skin Tone', 'Redness Reduction'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Skin Analysis</title>
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
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
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
      <?php if ($analysis_result): ?>
        <!-- Results View -->
        <div class="analysis-results">
          <div class="results-hero">
            <div class="hero-content">
              <h1>Your Personalized Skin Analysis</h1>
              <p>Based on your unique profile, here's your customized skincare roadmap</p>
            </div>
            
            <div class="score-card">
              <div class="score-circle" style="--score: <?php echo $analysis_result['skin_score']; ?>">
                <svg class="progress-ring" width="140" height="140">
                  <circle class="progress-ring-circle-bg" cx="70" cy="70" r="60"/>
                  <circle class="progress-ring-circle" cx="70" cy="70" r="60" 
                          style="stroke-dashoffset: <?php echo 377 - (377 * $analysis_result['skin_score'] / 100); ?>"/>
                </svg>
                <div class="score-content">
                  <div class="score-value"><?php echo $analysis_result['skin_score']; ?></div>
                  <div class="score-label">Score</div>
                </div>
              </div>
              <div class="score-description">
                <h2><?php echo $analysis_result['skin_score'] >= 80 ? 'Excellent!' : ($analysis_result['skin_score'] >= 70 ? 'Great Foundation!' : 'Good Start!'); ?></h2>
                <p><?php echo $analysis_result['skin_score'] >= 80 ? 'Your skin is in great condition! Keep up your routine.' : 'Your skin shows good potential with room for improvement.'; ?></p>
              </div>
            </div>
          </div>

          <div class="results-grid">
            <!-- Recommended Routine -->
            <div class="result-card">
              <div class="card-header">
                <div class="card-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                  </svg>
                </div>
                <h3>Daily Routine</h3>
              </div>
              <div class="routine-steps">
                <?php foreach ($analysis_result['recommendations']['routine'] as $index => $step): ?>
                  <div class="routine-step">
                    <div class="step-number"><?php echo $index + 1; ?></div>
                    <span><?php echo $step; ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Suggested Products -->
            <div class="result-card">
              <div class="card-header">
                <div class="card-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                  </svg>
                </div>
                <h3>Product Recommendations</h3>
              </div>
              <div class="product-recommendations">
                <?php if (!empty($analysis_result['recommendations']['products'])): ?>
                  <?php foreach ($analysis_result['recommendations']['products'] as $product): ?>
                    <div class="product-recommendation">
                      <div class="product-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span><?php echo $product; ?></span>
                      </div>
                      <a href="shopall.php?search=<?php echo urlencode($product); ?>" class="shop-link">
                        Shop
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <line x1="5" y1="12" x2="19" y2="12"/>
                          <polyline points="12 5 19 12 12 19"/>
                        </svg>
                      </a>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="empty-message">
                    <p>Your current routine is well-suited! Consider adding a serum for targeted benefits.</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Expert Tips -->
            <div class="result-card full-width">
              <div class="card-header">
                <div class="card-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                  </svg>
                </div>
                <h3>Expert Tips & Insights</h3>
              </div>
              <div class="expert-tips">
                <?php foreach ($analysis_result['recommendations']['tips'] as $tip): ?>
                  <div class="tip-item">
                    <div class="tip-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                      </svg>
                    </div>
                    <p><?php echo $tip; ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Analysis Summary -->
            <div class="result-card full-width">
              <div class="card-header">
                <div class="card-icon">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                  </svg>
                </div>
                <h3>Analysis Summary</h3>
              </div>
              <div class="summary-grid">
                <div class="summary-item">
                  <div class="summary-label">Skin Type</div>
                  <div class="summary-value"><?php echo $analysis_result['skin_type']; ?></div>
                </div>
                <div class="summary-item">
                  <div class="summary-label">Primary Concerns</div>
                  <div class="summary-value"><?php echo !empty($analysis_result['concerns']) ? implode(', ', $analysis_result['concerns']) : 'None specified'; ?></div>
                </div>
                <div class="summary-item">
                  <div class="summary-label">Recommended Timeline</div>
                  <div class="summary-value">Follow routine for 4-6 weeks</div>
                </div>
                <div class="summary-item">
                  <div class="summary-label">Next Analysis</div>
                  <div class="summary-value">Schedule in 6 weeks</div>
                </div>
              </div>
            </div>
          </div>

          <div class="results-actions">
            <a href="shopall.php" class="btn btn-primary">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              Shop Recommended Products
            </a>
            <a href="dashboard.php" class="btn btn-outline">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
              </svg>
              View Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-outline">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 6 2 18 2 18 9"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect x="6" y="14" width="12" height="8"/>
              </svg>
              Print Results
            </button>
            <a href="skin-analysis.php" class="btn btn-outline">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="1 4 1 10 7 10"/>
                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
              </svg>
              Retake Analysis
            </a>
          </div>
        </div>
      <?php else: ?>
        <!-- Analysis Form -->
        <div class="analysis-header">
          <h1>Personalized Skin Analysis</h1>
          <p>Answer a few questions to receive your customized skincare routine and product recommendations</p>
          <div class="progress-indicator">
            <div class="progress-step active" data-step="1">
              <div class="step-circle">1</div>
              <span>Skin Type</span>
            </div>
            <div class="progress-step" data-step="2">
              <div class="step-circle">2</div>
              <span>Concerns</span>
            </div>
            <div class="progress-step" data-step="3">
              <div class="step-circle">3</div>
              <span>Sensitivity</span>
            </div>
            <div class="progress-step" data-step="4">
              <div class="step-circle">4</div>
              <span>Lifestyle</span>
            </div>
            <div class="progress-step" data-step="5">
              <div class="step-circle">5</div>
              <span>Goals</span>
            </div>
          </div>
        </div>

        <form method="POST" class="analysis-form" id="analysisForm">
          <input type="hidden" name="submit_analysis" value="1">
          
          <!-- Skin Type -->
          <div class="form-section active" data-section="1">
            <div class="section-header">
              <h2>What's Your Skin Type?</h2>
              <p class="section-subtitle">Choose the option that best describes your skin</p>
            </div>
            <div class="options-grid">
              <?php foreach ($skin_types as $type): ?>
                <label class="option-card">
                  <input type="radio" name="skin_type" value="<?php echo $type; ?>" required>
                  <div class="option-content">
                    <div class="option-icon">
                      <?php 
                      switch ($type) {
                        case 'Normal': echo '😊'; break;
                        case 'Dry': echo '🌵'; break;
                        case 'Oily': echo '💧'; break;
                        case 'Combination': echo '🌓'; break;
                        case 'Sensitive': echo '⚠️'; break;
                      }
                      ?>
                    </div>
                    <h4><?php echo $type; ?></h4>
                    <p>
                      <?php 
                      switch ($type) {
                        case 'Normal': echo 'Balanced, clear, and not sensitive'; break;
                        case 'Dry': echo 'Flaky, rough, or tight feeling'; break;
                        case 'Oily': echo 'Shiny, greasy, enlarged pores'; break;
                        case 'Combination': echo 'Oily T-zone, dry cheeks'; break;
                        case 'Sensitive': echo 'Easily irritated, red, reactive'; break;
                      }
                      ?>
                    </p>
                  </div>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Skin Concerns -->
          <div class="form-section" data-section="2">
            <div class="section-header">
              <h2>Main Skin Concerns</h2>
              <p class="section-subtitle">Select all that apply to you</p>
            </div>
            <div class="concerns-grid">
              <?php foreach ($concerns_list as $concern): ?>
                <label class="checkbox-card">
                  <input type="checkbox" name="concerns[]" value="<?php echo $concern; ?>">
                  <div class="checkbox-content">
                    <span class="checkmark">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                    </span>
                    <span class="label"><?php echo $concern; ?></span>
                  </div>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Sensitivity -->
          <div class="form-section" data-section="3">
            <div class="section-header">
              <h2>Skin Sensitivity Level</h2>
              <p class="section-subtitle">How reactive is your skin to new products?</p>
            </div>
            <div class="sensitivity-scale">
              <?php foreach ($sensitivity_levels as $index => $level): ?>
                <label class="sensitivity-option">
                  <input type="radio" name="sensitivity" value="<?php echo $level; ?>" required>
                  <div class="sensitivity-content">
                    <div class="sensitivity-visual">
                      <?php for ($i = 0; $i <= $index; $i++): ?>
                        <span class="dot"></span>
                      <?php endfor; ?>
                    </div>
                    <span class="level-name"><?php echo $level; ?></span>
                  </div>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Lifestyle Factors -->
          <div class="form-section" data-section="4">
            <div class="section-header">
              <h2>Lifestyle Factors</h2>
              <p class="section-subtitle">These can significantly impact your skin health</p>
            </div>
            <div class="lifestyle-grid">
              <?php foreach ($lifestyle_factors as $factor): ?>
                <label class="checkbox-card">
                  <input type="checkbox" name="lifestyle[]" value="<?php echo $factor; ?>">
                  <div class="checkbox-content">
                    <span class="checkmark">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                    </span>
                    <span class="label"><?php echo $factor; ?></span>
                  </div>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Skin Goals -->
          <div class="form-section" data-section="5">
            <div class="section-header">
              <h2>Your Skin Goals</h2>
              <p class="section-subtitle">What do you want to achieve with your skincare?</p>
            </div>
            <div class="goals-grid">
              <?php foreach ($skin_goals as $goal): ?>
                <label class="checkbox-card">
                  <input type="checkbox" name="goals[]" value="<?php echo $goal; ?>">
                  <div class="checkbox-content">
                    <span class="checkmark">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                    </span>
                    <span class="label"><?php echo $goal; ?></span>
                  </div>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="form-navigation">
            <button type="button" class="btn btn-outline" id="prevBtn" style="display: none;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
              </svg>
              Previous
            </button>
            <button type="button" class="btn btn-primary" id="nextBtn">
              Next
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
              </svg>
            </button>
            <button type="submit" class="btn btn-primary btn-submit" id="submitBtn" style="display: none;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              Get My Analysis
            </button>
          </div>
        </form>
      <?php endif; ?>
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
        
        document.addEventListener('click', function() {
          dropdown.classList.remove('show');
        });
      }

      // Multi-step form navigation
      const form = document.getElementById('analysisForm');
      if (form) {
        let currentStep = 1;
        const totalSteps = 5;
        const sections = document.querySelectorAll('.form-section');
        const progressSteps = document.querySelectorAll('.progress-step');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        function showStep(step) {
          sections.forEach((section, index) => {
            section.classList.remove('active');
            if (index + 1 === step) {
              section.classList.add('active');
            }
          });

          progressSteps.forEach((progressStep, index) => {
            progressStep.classList.remove('active', 'completed');
            if (index + 1 < step) {
              progressStep.classList.add('completed');
            } else if (index + 1 === step) {
              progressStep.classList.add('active');
            }
          });

          // Update buttons
          prevBtn.style.display = step === 1 ? 'none' : 'inline-flex';
          nextBtn.style.display = step === totalSteps ? 'none' : 'inline-flex';
          submitBtn.style.display = step === totalSteps ? 'inline-flex' : 'none';

          // Scroll to top of form
          form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function validateStep(step) {
          const currentSection = document.querySelector(`.form-section[data-section="${step}"]`);
          const requiredInputs = currentSection.querySelectorAll('input[required]');
          
          for (let input of requiredInputs) {
            if (input.type === 'radio') {
              const radioGroup = currentSection.querySelectorAll(`input[name="${input.name}"]`);
              const isChecked = Array.from(radioGroup).some(radio => radio.checked);
              if (!isChecked) {
                return false;
              }
            }
          }
          return true;
        }

        nextBtn.addEventListener('click', function() {
          if (validateStep(currentStep)) {
            if (currentStep < totalSteps) {
              currentStep++;
              showStep(currentStep);
            }
          } else {
            alert('Please select an option before continuing.');
          }
        });

        prevBtn.addEventListener('click', function() {
          if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
          }
        });

        // Initialize
        showStep(currentStep);
      }
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
      --success-color: #10b981;
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

    .dropdown a:hover {
      background: var(--bg-light);
      color: var(--red-side);
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 2.5rem 0 4rem;
    }

    /* Analysis Header */
    .analysis-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .analysis-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--text-dark);
    }

    .analysis-header p {
      font-size: 1.1rem;
      color: var(--text-light);
      max-width: 700px;
      margin: 0 auto 2rem;
    }

    /* Progress Indicator */
    .progress-indicator {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 1rem;
      margin-top: 2rem;
    }

    .progress-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
      position: relative;
    }

    .progress-step:not(:last-child)::after {
      content: '';
      position: absolute;
      left: calc(100% + 0.5rem);
      top: 15px;
      width: 100%;
      height: 2px;
      background: var(--border-color);
    }

    .progress-step.completed:not(:last-child)::after,
    .progress-step.active:not(:last-child)::after {
      background: var(--red-side);
    }

    .step-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--bg-light);
      border: 2px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      color: var(--text-muted);
      transition: var(--transition);
    }

    .progress-step.active .step-circle {
      background: var(--red-side);
      border-color: var(--red-side);
      color: white;
    }

    .progress-step.completed .step-circle {
      background: var(--success-color);
      border-color: var(--success-color);
      color: white;
    }

    .progress-step span {
      font-size: 0.85rem;
      color: var(--text-muted);
      font-weight: 500;
    }

    .progress-step.active span,
    .progress-step.completed span {
      color: var(--text-dark);
      font-weight: 600;
    }

    /* Analysis Form */
    .analysis-form {
      background: var(--bg-card);
      padding: 3rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      max-width: 900px;
      margin: 0 auto;
    }

    .form-section {
      display: none;
      animation: fadeIn 0.4s ease;
    }

    .form-section.active {
      display: block;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .section-header {
      text-align: center;
      margin-bottom: 2.5rem;
    }

    .section-header h2 {
      color: var(--red-side);
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
    }

    .section-subtitle {
      color: var(--text-light);
      font-size: 1rem;
    }

    /* Option Cards */
    .options-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1.5rem;
    }

    .option-card {
      cursor: pointer;
      position: relative;
    }

    .option-card input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }

    .option-content {
      border: 3px solid var(--border-color);
      border-radius: var(--radius);
      padding: 2rem 1.5rem;
      text-align: center;
      transition: var(--transition);
      background: var(--bg-light);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .option-card:hover .option-content {
      border-color: var(--red-side);
      transform: translateY(-5px);
      box-shadow: var(--shadow);
    }

    .option-card input:checked ~ .option-content {
      border-color: var(--red-side);
      background: linear-gradient(135deg, #fff 0%, #ffe6e6 100%);
      box-shadow: var(--shadow-lg);
    }

    .option-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .option-content h4 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      color: var(--text-dark);
      font-weight: 600;
    }

    .option-content p {
      color: var(--text-light);
      font-size: 0.9rem;
      line-height: 1.5;
    }

    /* Checkbox Cards */
    .concerns-grid,
    .lifestyle-grid,
    .goals-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    .checkbox-card {
      cursor: pointer;
      position: relative;
    }

    .checkbox-card input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }

    .checkbox-content {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 1.25rem 1.5rem;
      background: var(--bg-light);
      border: 2px solid var(--border-color);
      border-radius: var(--radius-sm);
      transition: var(--transition);
    }

    .checkbox-card:hover .checkbox-content {
      border-color: var(--red-side);
      background: white;
    }

    .checkbox-card input:checked ~ .checkbox-content {
      border-color: var(--red-side);
      background: linear-gradient(135deg, #fff 0%, #ffe6e6 100%);
    }

    .checkmark {
      width: 24px;
      height: 24px;
      border: 2px solid var(--border-color);
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: var(--transition);
    }

    .checkmark svg {
      opacity: 0;
      transition: var(--transition);
    }

    .checkbox-card input:checked ~ .checkbox-content .checkmark {
      background: var(--red-side);
      border-color: var(--red-side);
    }

    .checkbox-card input:checked ~ .checkbox-content .checkmark svg {
      opacity: 1;
      stroke: white;
    }

    .checkbox-content .label {
      font-weight: 500;
      color: var(--text-dark);
    }

    /* Sensitivity Scale */
    .sensitivity-scale {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
    }

    .sensitivity-option {
      cursor: pointer;
    }

    .sensitivity-option input {
      position: absolute;
      opacity: 0;
    }

    .sensitivity-content {
      padding: 2rem 1.5rem;
      background: var(--bg-light);
      border: 3px solid var(--border-color);
      border-radius: var(--radius);
      text-align: center;
      transition: var(--transition);
    }

    .sensitivity-option:hover .sensitivity-content {
      border-color: var(--red-side);
      transform: translateY(-3px);
    }

    .sensitivity-option input:checked ~ .sensitivity-content {
      border-color: var(--red-side);
      background: linear-gradient(135deg, #fff 0%, #ffe6e6 100%);
      box-shadow: var(--shadow);
    }

    .sensitivity-visual {
      display: flex;
      justify-content: center;
      gap: 6px;
      margin-bottom: 1rem;
    }

    .dot {
      width: 10px;
      height: 10px;
      background: var(--border-color);
      border-radius: 50%;
      transition: var(--transition);
    }

    .sensitivity-option input:checked ~ .sensitivity-content .dot {
      background: var(--red-side);
    }

    .level-name {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    /* Form Navigation */
    .form-navigation {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 3rem;
      padding-top: 2rem;
      border-top: 1px solid var(--border-color);
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
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
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

    .btn-outline {
      background: transparent;
      color: var(--text-dark);
      border: 2px solid var(--border-color);
    }

    .btn-outline:hover {
      border-color: var(--red-side);
      color: var(--red-side);
      transform: translateY(-2px);
    }

    .btn-submit {
      padding: 1rem 2rem;
      font-size: 1.05rem;
    }

    /* Results Section */
    .analysis-results {
      max-width: 1100px;
      margin: 0 auto;
    }

    .results-hero {
      background: linear-gradient(135deg, var(--red-side) 0%, #A52A2A 100%);
      color: white;
      padding: 3rem;
      border-radius: var(--radius);
      margin-bottom: 2.5rem;
      box-shadow: var(--shadow-lg);
    }

    .hero-content {
      text-align: center;
      margin-bottom: 2rem;
    }

    .hero-content h1 {
      font-size: 2.5rem;
      margin-bottom: 0.75rem;
    }

    .hero-content p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .score-card {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 3rem;
      flex-wrap: wrap;
    }

    .score-circle {
      position: relative;
      width: 140px;
      height: 140px;
    }

    .progress-ring {
      transform: rotate(-90deg);
    }

    .progress-ring-circle-bg {
      fill: none;
      stroke: rgba(255, 255, 255, 0.2);
      stroke-width: 8;
    }

    .progress-ring-circle {
      fill: none;
      stroke: white;
      stroke-width: 8;
      stroke-linecap: round;
      stroke-dasharray: 377;
      transition: stroke-dashoffset 1s ease;
    }

    .score-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
    }

    .score-value {
      font-size: 2.5rem;
      font-weight: 800;
      line-height: 1;
    }

    .score-label {
      font-size: 0.85rem;
      opacity: 0.9;
    }

    .score-description h2 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }

    .score-description p {
      font-size: 1.05rem;
      opacity: 0.9;
    }

    /* Results Grid */
    .results-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-bottom: 2.5rem;
    }

    .result-card {
      background: var(--bg-card);
      padding: 2rem;
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
      box-shadow: var(--shadow);
    }

    .result-card.full-width {
      grid-column: 1 / -1;
    }

    .card-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--bg-light);
    }

    .card-icon {
      width: 45px;
      height: 45px;
      background: linear-gradient(135deg, var(--red-side) 0%, #A52A2A 100%);
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      flex-shrink: 0;
    }

    .card-header h3 {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    /* Routine Steps */
    .routine-steps {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .routine-step {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: var(--radius-sm);
      transition: var(--transition);
    }

    .routine-step:hover {
      background: white;
      box-shadow: var(--shadow-sm);
    }

    .step-number {
      width: 32px;
      height: 32px;
      background: var(--red-side);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      flex-shrink: 0;
    }

    /* Product Recommendations */
    .product-recommendations {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .product-recommendation {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: var(--radius-sm);
      transition: var(--transition);
    }

    .product-recommendation:hover {
      background: white;
      box-shadow: var(--shadow-sm);
    }

    .product-info {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .product-info svg {
      color: var(--success-color);
      flex-shrink: 0;
    }

    .shop-link {
      color: var(--red-side);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 0.25rem;
      transition: var(--transition);
    }

    .shop-link:hover {
      color: var(--red-hover);
      gap: 0.5rem;
    }

    .empty-message p {
      color: var(--text-light);
      font-style: italic;
    }

    /* Expert Tips */
    .expert-tips {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .tip-item {
      display: flex;
      gap: 1rem;
      padding: 1.25rem;
      background: var(--bg-light);
      border-radius: var(--radius-sm);
      border-left: 4px solid var(--red-side);
    }

    .tip-icon {
      width: 40px;
      height: 40px;
      background: var(--red-side);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      flex-shrink: 0;
    }

    .tip-item p {
      color: var(--text-dark);
      line-height: 1.6;
    }

    /* Summary Grid */
    .summary-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.5rem;
    }

    .summary-item {
      padding: 1.5rem;
      background: var(--bg-light);
      border-radius: var(--radius-sm);
      text-align: center;
    }

    .summary-label {
      color: var(--text-light);
      font-size: 0.85rem;
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
    }

    .summary-value {
      color: var(--text-dark);
      font-size: 1.1rem;
      font-weight: 600;
    }

    /* Results Actions */
    .results-actions {
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
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
      .results-grid {
        grid-template-columns: 1fr;
      }

      .expert-tips {
        grid-template-columns: 1fr;
      }

      .summary-grid {
        grid-template-columns: 1fr 1fr;
      }

      .progress-indicator {
        gap: 0.5rem;
      }

      .progress-step span {
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

      .analysis-header h1,
      .hero-content h1 {
        font-size: 2rem;
      }

      .analysis-form {
        padding: 2rem;
      }

      .options-grid {
        grid-template-columns: 1fr;
      }

      .concerns-grid,
      .lifestyle-grid,
      .goals-grid {
        grid-template-columns: 1fr;
      }

      .sensitivity-scale {
        grid-template-columns: 1fr 1fr;
      }

      .score-card {
        flex-direction: column;
        gap: 2rem;
      }

      .results-hero {
        padding: 2rem;
      }

      .summary-grid {
        grid-template-columns: 1fr;
      }

      .results-actions {
        flex-direction: column;
      }

      .results-actions .btn {
        width: 100%;
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

      .analysis-form {
        padding: 1.5rem;
      }

      .section-header h2 {
        font-size: 1.5rem;
      }

      .option-content {
        padding: 1.5rem 1rem;
      }

      .option-icon {
        font-size: 2.5rem;
      }

      .result-card {
        padding: 1.5rem;
      }

      .card-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .nav {
        gap: 0.75rem;
      }

      .nav a {
        font-size: 0.9rem;
      }

      .progress-indicator {
        overflow-x: auto;
        padding-bottom: 1rem;
      }

      .form-navigation {
        flex-direction: column;
      }

      .form-navigation .btn {
        width: 100%;
      }
    }

    /* Print Styles */
    @media print {
      .header,
      .footer,
      .results-actions {
        display: none;
      }

      .analysis-results {
        box-shadow: none;
      }

      .result-card {
        break-inside: avoid;
        page-break-inside: avoid;
      }
    }
  </style>
</body>
</html>