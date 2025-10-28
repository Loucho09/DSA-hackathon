<?php
session_start();

// Sample product data
$products = [
    ['id' => 1, 'name' => 'Hydrating Facial Cleanser', 'category' => 'Cleansers', 'price' => 28.00, 'image' => 'product1.jpg'],
    ['id' => 2, 'name' => 'Vitamin C Brightening Serum', 'category' => 'Serums', 'price' => 45.00, 'image' => 'product2.jpg'],
    ['id' => 3, 'name' => 'Anti-Aging Night Cream', 'category' => 'Moisturizers', 'price' => 52.00, 'image' => 'product3.jpg'],
    ['id' => 4, 'name' => 'SPF 50 Sun Protection', 'category' => 'Sunscreen', 'price' => 35.00, 'image' => 'product4.jpg'],
    ['id' => 5, 'name' => 'Exfoliating Treatment', 'category' => 'Treatments', 'price' => 38.00, 'image' => 'product5.jpg'],
    ['id' => 6, 'name' => 'Hydrating Face Mask', 'category' => 'Masks', 'price' => 25.00, 'image' => 'product6.jpg'],
];

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$priceFilter = isset($_GET['price']) ? $_GET['price'] : '';

// Filter products based on search criteria
$filteredProducts = $products;

if (!empty($searchQuery)) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($searchQuery) {
        return stripos($product['name'], $searchQuery) !== false || 
               stripos($product['category'], $searchQuery) !== false;
    });
}

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

$categories = array_unique(array_column($products, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Search Products</title>
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
        <h1>Search Products</h1>
        <p class="subtitle">Find your perfect skincare products</p>
      </div>

      <!-- Search Section -->
      <div class="search-section">
        <form method="GET" action="search.php" class="search-form">
          <div class="search-bar">
            <input 
              type="text" 
              name="q" 
              placeholder="Search products..." 
              value="<?php echo htmlspecialchars($searchQuery); ?>"
              class="search-input"
            >
            <button type="submit" class="search-btn">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
              </svg>
            </button>
          </div>

          <div class="filters">
            <div class="filter-group">
              <label for="category">Category</label>
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
              <label for="price">Price Range</label>
              <select name="price" id="price" onchange="this.form.submit()">
                <option value="">All Prices</option>
                <option value="under25" <?php echo $priceFilter === 'under25' ? 'selected' : ''; ?>>Under $25</option>
                <option value="25-50" <?php echo $priceFilter === '25-50' ? 'selected' : ''; ?>>$25 - $50</option>
                <option value="over50" <?php echo $priceFilter === 'over50' ? 'selected' : ''; ?>>Over $50</option>
              </select>
            </div>

            <button type="button" class="clear-filters" onclick="clearFilters()">Clear Filters</button>
          </div>
        </form>
      </div>

      <!-- Results Section -->
      <div class="results-section">
        <div class="results-header">
          <h2>
            <?php if (empty($searchQuery) && empty($categoryFilter) && empty($priceFilter)): ?>
              All Products
            <?php else: ?>
              Search Results
            <?php endif; ?>
            <span class="results-count">(<?php echo count($filteredProducts); ?> products found)</span>
          </h2>
        </div>

        <?php if (empty($filteredProducts)): ?>
          <div class="no-results">
            <h3>No products found</h3>
            <p>Try adjusting your search criteria or browse all products.</p>
            <a href="search.php" class="btn btn-primary">View All Products</a>
          </div>
        <?php else: ?>
          <div class="products-grid">
            <?php foreach ($filteredProducts as $product): ?>
              <div class="product-card">
                <div class="product-image">
                  <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjBGMEYwIi8+CjxwYXRoIGQ9Ik04MCA2MEgxMjBWOTBIMTBWNzBINjBWNjBaIiBmaWxsPSIjQ0VDRUNFIi8+CjxjaXJjbGUgY3g9IjEwMCIgY3k9IjExMCIgcj0iMjAiIGZpbGw9IiNDRUNFQ0UiLz4KPC9zdmc+'">
                </div>
                <div class="product-info">
                  <span class="product-category"><?php echo $product['category']; ?></span>
                  <h3 class="product-name"><?php echo $product['name']; ?></h3>
                  <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                  <button class="btn btn-outline">Add to Cart</button>
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
      window.location.href = 'search.php';
    }

    // Add to cart functionality
    document.addEventListener('DOMContentLoaded', function() {
      const addToCartButtons = document.querySelectorAll('.btn-outline');
      
      addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
          const productCard = this.closest('.product-card');
          const productName = productCard.querySelector('.product-name').textContent;
          const productPrice = productCard.querySelector('.product-price').textContent;
          
          // Show added to cart message
          const originalText = this.textContent;
          this.textContent = 'Added!';
          this.disabled = true;
          
          setTimeout(() => {
            this.textContent = originalText;
            this.disabled = false;
          }, 2000);
          
          console.log('Added to cart:', productName, productPrice);
        });
      });
    });
  </script>

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

    /* Search Section */
    .search-section {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
      border: 1px solid var(--border-color);
    }

    .search-form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .search-bar {
      display: flex;
      gap: 0;
      max-width: 600px;
      margin: 0 auto;
      width: 100%;
    }

    .search-input {
      flex: 1;
      padding: 12px 16px;
      border: 2px solid var(--input-border);
      border-right: none;
      border-radius: var(--radius) 0 0 var(--radius);
      background: var(--input-bg);
      font-size: 1rem;
      transition: var(--transition);
    }

    .search-input:focus {
      outline: none;
      border-color: var(--input-focus);
      background: white;
    }

    .search-btn {
      background: var(--btn-bg);
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 0 var(--radius) var(--radius) 0;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .search-btn:hover {
      background: var(--btn-hover);
    }

    .filters {
      display: flex;
      gap: 1.5rem;
      align-items: end;
      flex-wrap: wrap;
      justify-content: center;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .filter-group label {
      font-weight: 500;
      color: var(--text-dark);
      font-size: 0.9rem;
    }

    .filter-group select {
      padding: 8px 12px;
      border: 2px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
      font-size: 0.9rem;
      cursor: pointer;
      transition: var(--transition);
    }

    .filter-group select:focus {
      outline: none;
      border-color: var(--input-focus);
    }

    .clear-filters {
      background: transparent;
      color: var(--red-side);
      border: 1px solid var(--red-side);
      padding: 8px 16px;
      border-radius: var(--radius);
      cursor: pointer;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .clear-filters:hover {
      background: var(--red-side);
      color: white;
    }

    /* Results Section */
    .results-section {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .results-header {
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border-color);
    }

    .results-header h2 {
      color: var(--text-dark);
      font-size: 1.5rem;
      font-weight: 600;
    }

    .results-count {
      color: var(--text-light);
      font-weight: 400;
      font-size: 1rem;
    }

    .no-results {
      text-align: center;
      padding: 3rem 2rem;
    }

    .no-results h3 {
      color: var(--text-dark);
      margin-bottom: 1rem;
      font-size: 1.3rem;
    }

    .no-results p {
      color: var(--text-light);
      margin-bottom: 2rem;
    }

    /* Products Grid */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    .product-card {
      background: var(--bg-light);
      border-radius: var(--radius);
      overflow: hidden;
      transition: var(--transition);
      border: 1px solid var(--border-color);
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow);
    }

    .product-image {
      width: 100%;
      height: 200px;
      background: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .product-info {
      padding: 1.5rem;
    }

    .product-category {
      color: var(--red-side);
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .product-name {
      color: var(--text-dark);
      margin: 0.5rem 0;
      font-size: 1.1rem;
      font-weight: 600;
      line-height: 1.4;
    }

    .product-price {
      color: var(--btn-bg);
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .btn {
      padding: 10px 16px;
      border: none;
      border-radius: var(--radius);
      font-size: 0.9rem;
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
    }

    .btn-outline {
      background: transparent;
      color: var(--btn-bg);
      border: 2px solid var(--btn-bg);
      width: 100%;
    }

    .btn-outline:hover {
      background: var(--btn-bg);
      color: white;
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

      .filters {
        flex-direction: column;
        align-items: stretch;
      }

      .filter-group {
        width: 100%;
      }

      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      }

      .search-section,
      .results-section {
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

      .products-grid {
        grid-template-columns: 1fr;
      }

      .search-bar {
        flex-direction: column;
      }

      .search-input {
        border-right: 2px solid var(--input-border);
        border-radius: var(--radius);
        margin-bottom: 0.5rem;
      }

      .search-btn {
        border-radius: var(--radius);
      }
    }
  </style>
</body>
</html>