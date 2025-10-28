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

// Sample product data (in real app, this would come from database)
$products = [
    ['id' => 1, 'name' => 'Hydrating Facial Cleanser', 'category' => 'Cleansers', 'price' => 28.00, 'image' => 'product1.jpg', 'rating' => 4.5, 'description' => 'Gentle daily cleanser for all skin types'],
    ['id' => 2, 'name' => 'Vitamin C Brightening Serum', 'category' => 'Serums', 'price' => 45.00, 'image' => 'product2.jpg', 'rating' => 4.8, 'description' => 'Powerful antioxidant serum'],
    ['id' => 3, 'name' => 'Anti-Aging Night Cream', 'category' => 'Moisturizers', 'price' => 52.00, 'image' => 'product3.jpg', 'rating' => 4.6, 'description' => 'Intensive overnight treatment'],
    ['id' => 4, 'name' => 'SPF 50 Sun Protection', 'category' => 'Sunscreen', 'price' => 35.00, 'image' => 'product4.jpg', 'rating' => 4.7, 'description' => 'Broad spectrum protection'],
    ['id' => 5, 'name' => 'Exfoliating Treatment', 'category' => 'Treatments', 'price' => 38.00, 'image' => 'product5.jpg', 'rating' => 4.4, 'description' => 'Gentle exfoliating formula'],
    ['id' => 6, 'name' => 'Hydrating Face Mask', 'category' => 'Masks', 'price' => 25.00, 'image' => 'product6.jpg', 'rating' => 4.9, 'description' => 'Deep hydration treatment'],
    ['id' => 7, 'name' => 'Revitalizing Eye Cream', 'category' => 'Treatments', 'price' => 42.00, 'image' => 'product7.jpg', 'rating' => 4.5, 'description' => 'Reduces fine lines and puffiness'],
    ['id' => 8, 'name' => 'Balancing Toner', 'category' => 'Toners', 'price' => 30.00, 'image' => 'product8.jpg', 'rating' => 4.3, 'description' => 'pH-balanced formula'],
];

$categories = array_unique(array_column($products, 'category'));

// Handle filters
$categoryFilter = $_GET['category'] ?? '';
$priceFilter = $_GET['price'] ?? '';
$sortBy = $_GET['sort'] ?? 'name';

// Filter products
$filteredProducts = $products;

if (!empty($categoryFilter) && $categoryFilter !== 'all') {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($categoryFilter) {
        return $product['category'] === $categoryFilter;
    });
}

if (!empty($priceFilter)) {
    switch ($priceFilter) {
        case 'under25':
            $filteredProducts = array_filter($filteredProducts, function($product) {
                return $product['price'] < 25;
            });
            break;
        case '25-50':
            $filteredProducts = array_filter($filteredProducts, function($product) {
                return $product['price'] >= 25 && $product['price'] <= 50;
            });
            break;
        case 'over50':
            $filteredProducts = array_filter($filteredProducts, function($product) {
                return $product['price'] > 50;
            });
            break;
    }
}

// Sort products
usort($filteredProducts, function($a, $b) use ($sortBy) {
    switch ($sortBy) {
        case 'price_low':
            return $a['price'] <=> $b['price'];
        case 'price_high':
            return $b['price'] <=> $a['price'];
        case 'rating':
            return $b['rating'] <=> $a['rating'];
        default:
            return $a['name'] <=> $b['name'];
    }
});

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    // In real app, you would add to cart in database
    $message = "Product added to cart successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Shop All Products</title>
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
        <a href="store_locator.php">Stores</a>
        <a href="faqs.php">FAQs</a>
        <div class="user-menu">
          <img src="<?php echo $user['image'] ?: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGMEYwRjAiLz4KPGNpcmNsZSBjeD0iMjAiIGN5PSIxNiIgcj0iNSIgZmlsbD0iI0NFQ0VDRSIvPjxwYXRoIGQ9Ik0yOCAzMGMwLTQuNDE4LTQuNDU4LTgtMTAtOFM4IDI1LjU4MiA4IDMwIiBmaWxsPSIjQ0VDRUNFIi8+Cjwvc3ZnPgo='; ?>" 
               alt="Profile" class="user-avatar">
          <div class="dropdown">
            <a href="dashboard.php">Dashboard</a>
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
      <!-- Page Header -->
      <div class="page-header">
        <div class="header-content">
          <h1>Shop All Products</h1>
          <p class="subtitle">Discover our complete collection of premium skincare solutions</p>
        </div>
        <div class="header-actions">
          <a href="#" class="cart-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"/>
              <circle cx="20" cy="21" r="1"/>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <span class="cart-count">0</span>
          </a>
        </div>
      </div>

      <?php if (isset($message)): ?>
        <div class="message success">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
          <?php echo $message; ?>
        </div>
      <?php endif; ?>

      <!-- Filters and Sort -->
      <div class="shop-controls">
        <div class="controls-header">
          <h3>Filter & Sort</h3>
          <button type="button" class="clear-filters" onclick="clearFilters()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"/>
              <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
            Clear All
          </button>
        </div>
        
        <form method="GET" class="filters-form" id="filtersForm">
          <div class="filter-group">
            <label for="category">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
              </svg>
              Category
            </label>
            <select name="category" id="category" onchange="this.form.submit()">
              <option value="all">All Categories</option>
              <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category; ?>" <?php echo $categoryFilter === $category ? 'selected' : ''; ?>>
                  <?php echo $category; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="filter-group">
            <label for="price">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
              Price Range
            </label>
            <select name="price" id="price" onchange="this.form.submit()">
              <option value="">All Prices</option>
              <option value="under25" <?php echo $priceFilter === 'under25' ? 'selected' : ''; ?>>Under $25</option>
              <option value="25-50" <?php echo $priceFilter === '25-50' ? 'selected' : ''; ?>>$25 - $50</option>
              <option value="over50" <?php echo $priceFilter === 'over50' ? 'selected' : ''; ?>>Over $50</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="sort">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="4" y1="21" x2="4" y2="14"/>
                <line x1="4" y1="10" x2="4" y2="3"/>
                <line x1="12" y1="21" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12" y2="3"/>
                <line x1="20" y1="21" x2="20" y2="16"/>
                <line x1="20" y1="12" x2="20" y2="3"/>
                <line x1="1" y1="14" x2="7" y2="14"/>
                <line x1="9" y1="8" x2="15" y2="8"/>
                <line x1="17" y1="16" x2="23" y2="16"/>
              </svg>
              Sort By
            </label>
            <select name="sort" id="sort" onchange="this.form.submit()">
              <option value="name" <?php echo $sortBy === 'name' ? 'selected' : ''; ?>>Name</option>
              <option value="price_low" <?php echo $sortBy === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
              <option value="price_high" <?php echo $sortBy === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
              <option value="rating" <?php echo $sortBy === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
            </select>
          </div>
        </form>
      </div>

      <!-- Products Section -->
      <div class="products-section">
        <div class="results-header">
          <h2>
            <?php if (!empty($categoryFilter) && $categoryFilter !== 'all'): ?>
              <?php echo $categoryFilter; ?>
            <?php elseif (!empty($priceFilter)): ?>
              Filtered Products
            <?php else: ?>
              All Products
            <?php endif; ?>
          </h2>
          <span class="results-count"><?php echo count($filteredProducts); ?> products found</span>
        </div>

        <?php if (empty($filteredProducts)): ?>
          <div class="no-results">
            <div class="no-results-icon">🔍</div>
            <h3>No products found</h3>
            <p>Try adjusting your filters or browse all our amazing products.</p>
            <a href="shopall.php" class="btn btn-primary">View All Products</a>
          </div>
        <?php else: ?>
          <div class="products-grid">
            <?php foreach ($filteredProducts as $product): ?>
              <div class="product-card">
                <div class="product-badge">New</div>
                <div class="product-image">
                  <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" 
                       onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDMwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjRjBGMEYwIi8+CjxjaXJjbGUgY3g9IjE1MCIgY3k9IjEyMCIgcj0iNDAiIGZpbGw9IiNDRUNFQ0UiLz4KPHBhdGggZD0iTTEwMCAxODBIMjAwVjIyMEgxMDBWMTgwWiIgZmlsbD0iI0NFQ0VDRSIvPgo8L3N2Zz4='">
                  <div class="product-overlay">
                    <button class="wishlist-btn" data-product-id="<?php echo $product['id']; ?>" title="Add to wishlist">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                      </svg>
                    </button>
                    <button class="quick-view" data-product-id="<?php echo $product['id']; ?>" title="Quick view">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                      </svg>
                    </button>
                  </div>
                </div>
                <div class="product-info">
                  <span class="product-category"><?php echo $product['category']; ?></span>
                  <h3 class="product-name"><?php echo $product['name']; ?></h3>
                  <p class="product-description"><?php echo $product['description']; ?></p>
                  <div class="product-rating">
                    <div class="stars">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo $i <= floor($product['rating']) ? 'filled' : ($i - 0.5 <= $product['rating'] ? 'half' : ''); ?>">★</span>
                      <?php endfor; ?>
                    </div>
                    <span class="rating-value"><?php echo $product['rating']; ?></span>
                  </div>
                  <div class="product-footer">
                    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                    <form method="POST" class="add-to-cart-form">
                      <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                      <button type="submit" name="add_to_cart" class="btn btn-cart">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="9" cy="21" r="1"/>
                          <circle cx="20" cy="21" r="1"/>
                          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        Add to Cart
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
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
    function clearFilters() {
      window.location.href = 'shopall.php';
    }

    document.addEventListener('DOMContentLoaded', function() {
      // User menu dropdown
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

      // Wishlist functionality
      const wishlistButtons = document.querySelectorAll('.wishlist-btn');
      
      wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          const productId = this.dataset.productId;
          
          this.classList.toggle('active');
          
          const svg = this.querySelector('svg');
          if (this.classList.contains('active')) {
            svg.setAttribute('fill', 'currentColor');
          } else {
            svg.setAttribute('fill', 'none');
          }
          
          // Show feedback
          showToast('Product added to wishlist!');
        });
      });

      // Quick view functionality
      const quickViewButtons = document.querySelectorAll('.quick-view');
      quickViewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          const productId = this.dataset.productId;
          showToast('Quick view coming soon!');
        });
      });

      // Add to cart animation
      const cartForms = document.querySelectorAll('.add-to-cart-form');
      cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          const button = this.querySelector('button');
          button.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg> Added!';
          button.disabled = true;
          
          setTimeout(() => {
            button.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> Add to Cart';
            button.disabled = false;
          }, 2000);
        });
      });
    });

    // Toast notification
    function showToast(message) {
      const toast = document.createElement('div');
      toast.className = 'toast';
      toast.textContent = message;
      document.body.appendChild(toast);
      
      setTimeout(() => toast.classList.add('show'), 100);
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }
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
      max-width: 1280px;
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
      padding: 2.5rem 0 4rem;
    }

    /* Page Header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2.5rem;
      padding-bottom: 1.5rem;
      border-bottom: 2px solid var(--border-color);
    }

    .header-content h1 {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .subtitle {
      color: var(--text-light);
      font-size: 1.1rem;
    }

    .header-actions {
      display: flex;
      gap: 1rem;
    }

    .cart-link {
      position: relative;
      background: var(--red-side);
      color: white;
      padding: 0.75rem 1.25rem;
      border-radius: var(--radius-sm);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 600;
      transition: var(--transition);
    }

    .cart-link:hover {
      background: var(--red-hover);
      transform: translateY(-2px);
    }

    .cart-count {
      background: white;
      color: var(--red-side);
      padding: 0.2rem 0.5rem;
      border-radius: 10px;
      font-size: 0.8rem;
      font-weight: 700;
    }

    /* Messages */
    .message {
      padding: 1rem 1.5rem;
      border-radius: var(--radius-sm);
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-weight: 500;
      animation: slideIn 0.3s ease;
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

    .message.success {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }

    /* Shop Controls */
    .shop-controls {
      background: var(--bg-card);
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      margin-bottom: 2.5rem;
      border: 1px solid var(--border-color);
    }

    .controls-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border-color);
    }

    .controls-header h3 {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .clear-filters {
      background: transparent;
      color: var(--red-side);
      border: 2px solid var(--red-side);
      padding: 0.5rem 1rem;
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 600;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .clear-filters:hover {
      background: var(--red-side);
      color: white;
      transform: translateY(-2px);
    }

    .filters-form {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .filter-group label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .filter-group select {
      padding: 0.75rem 1rem;
      border: 2px solid var(--border-color);
      border-radius: var(--radius-sm);
      background: var(--bg-light);
      font-size: 0.95rem;
      cursor: pointer;
      transition: var(--transition);
      font-family: inherit;
    }

    .filter-group select:hover {
      border-color: var(--text-light);
    }

    .filter-group select:focus {
      outline: none;
      border-color: var(--red-side);
      background: white;
    }

    /* Products Section */
    .products-section {
      margin-top: 2rem;
    }

    .results-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .results-header h2 {
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .results-count {
      color: var(--text-light);
      font-size: 1rem;
      font-weight: 500;
    }

    /* Products Grid */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 2rem;
    }

    .product-card {
      background: var(--bg-card);
      border-radius: var(--radius);
      overflow: hidden;
      transition: var(--transition);
      border: 1px solid var(--border-color);
      position: relative;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-lg);
      border-color: transparent;
    }

    .product-badge {
      position: absolute;
      top: 12px;
      left: 12px;
      background: var(--red-side);
      color: white;
      padding: 0.35rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      z-index: 2;
    }

    .product-image {
      position: relative;
      width: 100%;
      height: 280px;
      background: var(--bg-light);
      overflow: hidden;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: var(--transition);
    }

    .product-card:hover .product-image img {
      transform: scale(1.1);
    }

    .product-overlay {
      position: absolute;
      top: 0;
      right: 0;
      padding: 12px;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      opacity: 0;
      transition: var(--transition);
    }

    .product-card:hover .product-overlay {
      opacity: 1;
    }

    .wishlist-btn,
    .quick-view {
      background: rgba(255, 255, 255, 0.95);
      border: none;
      width: 42px;
      height: 42px;
      border-radius: 50%;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-dark);
    }

    .wishlist-btn:hover,
    .quick-view:hover {
      background: white;
      transform: scale(1.1);
      box-shadow: var(--shadow);
    }

    .wishlist-btn.active {
      background: var(--red-side);
      color: white;
    }

    .wishlist-btn:hover {
      color: var(--red-side);
    }

    .wishlist-btn.active:hover {
      color: white;
    }

    /* Product Info */
    .product-info {
      padding: 1.5rem;
    }

    .product-category {
      color: var(--red-side);
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      display: block;
      margin-bottom: 0.5rem;
    }

    .product-name {
      color: var(--text-dark);
      font-size: 1.1rem;
      font-weight: 600;
      line-height: 1.4;
      margin-bottom: 0.5rem;
      min-height: 2.8em;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .product-description {
      color: var(--text-light);
      font-size: 0.85rem;
      line-height: 1.5;
      margin-bottom: 1rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .product-rating {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .stars {
      display: flex;
      gap: 0.15rem;
    }

    .star {
      color: #e5e7eb;
      font-size: 1rem;
    }

    .star.filled {
      color: #fbbf24;
    }

    .star.half {
      background: linear-gradient(90deg, #fbbf24 50%, #e5e7eb 50%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .rating-value {
      color: var(--text-light);
      font-size: 0.85rem;
      font-weight: 500;
    }

    .product-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      padding-top: 1rem;
      border-top: 1px solid var(--border-color);
    }

    .product-price {
      color: var(--red-side);
      font-size: 1.4rem;
      font-weight: 700;
      margin: 0;
    }

    .add-to-cart-form {
      flex: 1;
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

    .btn-cart {
      background: var(--text-dark);
      color: white;
      width: 100%;
      font-size: 0.9rem;
    }

    .btn-cart:hover {
      background: var(--red-side);
      transform: translateY(-2px);
    }

    .btn-cart:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* No Results */
    .no-results {
      text-align: center;
      padding: 4rem 2rem;
      background: var(--bg-card);
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
    }

    .no-results-icon {
      font-size: 5rem;
      margin-bottom: 1.5rem;
      opacity: 0.5;
    }

    .no-results h3 {
      color: var(--text-dark);
      font-size: 1.5rem;
      margin-bottom: 0.75rem;
    }

    .no-results p {
      color: var(--text-light);
      margin-bottom: 2rem;
      font-size: 1.05rem;
    }

    /* Toast Notification */
    .toast {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: var(--text-dark);
      color: white;
      padding: 1rem 1.5rem;
      border-radius: var(--radius-sm);
      box-shadow: var(--shadow-lg);
      opacity: 0;
      transform: translateY(20px);
      transition: var(--transition);
      z-index: 1000;
    }

    .toast.show {
      opacity: 1;
      transform: translateY(0);
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
      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
      }

      .filters-form {
        grid-template-columns: 1fr;
      }

      .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
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

      .header-content h1 {
        font-size: 2rem;
      }

      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
      }

      .product-card {
        border-radius: var(--radius-sm);
      }

      .product-image {
        height: 220px;
      }

      .results-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }

      .shop-controls {
        padding: 1.5rem;
      }

      .footer-sections {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .toast {
        bottom: 1rem;
        right: 1rem;
        left: 1rem;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 0 16px;
      }

      .products-grid {
        grid-template-columns: 1fr;
      }

      .product-footer {
        flex-direction: column;
        align-items: stretch;
      }

      .product-price {
        text-align: center;
      }

      .nav {
        gap: 0.75rem;
      }

      .nav a {
        font-size: 0.9rem;
      }
    }
  </style>
</body>
</html>