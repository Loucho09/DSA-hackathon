<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

$faq_categories = [
    'General' => [
        [
            'question' => 'What is Dermaluxe?',
            'answer' => 'Dermaluxe is a premium skincare brand dedicated to providing scientifically-backed, personalized skincare solutions. We combine cutting-edge dermatological research with natural ingredients to create products that deliver real results for all skin types.'
        ],
        [
            'question' => 'Are your products suitable for all skin types?',
            'answer' => 'Yes! We offer products specifically formulated for different skin types including normal, dry, oily, combination, and sensitive skin. Our skin analysis tool can help you identify your skin type and recommend the best products for your needs.'
        ],
        [
            'question' => 'Are your products cruelty-free?',
            'answer' => 'Absolutely! All Dermaluxe products are certified cruelty-free and never tested on animals. We are also committed to vegan-friendly formulations wherever possible.'
        ]
    ],
    'Products' => [
        [
            'question' => 'How do I choose the right products for my skin?',
            'answer' => 'We recommend taking our free online Skin Analysis quiz, which will assess your skin type, concerns, and goals. Based on your results, we\'ll provide personalized product recommendations. You can also visit any of our stores for a complimentary consultation with our skincare experts.'
        ],
        [
            'question' => 'What ingredients do you use in your products?',
            'answer' => 'We use a combination of proven active ingredients and natural botanical extracts. All our ingredients are carefully selected based on clinical research and safety profiles. We maintain complete transparency with full ingredient lists on every product page and package.'
        ],
        [
            'question' => 'How long does it take to see results?',
            'answer' => 'Results vary depending on the product and your skin concerns. Generally, you may notice improvements in hydration and texture within 1-2 weeks. For concerns like fine lines, dark spots, or acne, visible results typically appear after 4-8 weeks of consistent use.'
        ],
        [
            'question' => 'Can I use multiple Dermaluxe products together?',
            'answer' => 'Yes! Our products are designed to work synergistically. We recommend following a complete routine: cleanser, toner, serum, moisturizer, and sunscreen. Our skin analysis tool will suggest the optimal routine for your needs.'
        ]
    ],
    'Orders & Shipping' => [
        [
            'question' => 'How long does shipping take?',
            'answer' => 'For Metro Manila orders, delivery typically takes 1-3 business days. Provincial orders take 3-7 business days. We offer same-day delivery in select Metro Manila areas for orders placed before 12PM.'
        ],
        [
            'question' => 'Do you ship internationally?',
            'answer' => 'Currently, we ship within the Philippines only. We\'re working on expanding our international shipping options and will announce updates on our website and social media channels.'
        ],
        [
            'question' => 'How can I track my order?',
            'answer' => 'Once your order ships, you\'ll receive a tracking number via email and SMS. You can also track your order status by logging into your account and visiting the "Orders" section in your dashboard.'
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept credit/debit cards (Visa, Mastercard, JCB, American Express), GCash, PayMaya, bank transfers, and cash on delivery (COD) for select areas.'
        ]
    ],
    'Returns & Exchanges' => [
        [
            'question' => 'What is your return policy?',
            'answer' => 'We offer a 30-day return policy for unopened products in their original packaging. If you\'re not satisfied with your purchase, you can return it for a full refund or exchange within 30 days of delivery.'
        ],
        [
            'question' => 'Can I return opened products?',
            'answer' => 'We understand that skincare is personal. If you experience an adverse reaction or are unsatisfied with an opened product, please contact our customer service team within 14 days. We handle these cases individually and will work to find a suitable solution.'
        ],
        [
            'question' => 'How do I initiate a return?',
            'answer' => 'To start a return, log into your account, go to "Order History," select the order, and click "Request Return." You can also contact our customer service team via email at support@dermaluxe.com or call us at +63 912 345 6789.'
        ]
    ],
    'Account & Membership' => [
        [
            'question' => 'Do I need an account to make a purchase?',
            'answer' => 'No, you can checkout as a guest. However, creating an account allows you to track orders, save your skincare profile, access exclusive member benefits, and enjoy a faster checkout experience.'
        ],
        [
            'question' => 'What are the benefits of Premium Membership?',
            'answer' => 'Premium Members enjoy 15% off all purchases, free shipping on all orders, early access to new products, birthday rewards, priority customer service, and monthly skincare tips from our dermatologists.'
        ],
        [
            'question' => 'How do I upgrade to Premium Membership?',
            'answer' => 'You can upgrade to Premium Membership from your account dashboard. Premium Membership costs ₱999/year and pays for itself with just a few purchases!'
        ]
    ],
    'Skin Care Tips' => [
        [
            'question' => 'What is the correct order to apply skincare products?',
            'answer' => 'The general rule is to apply products from thinnest to thickest consistency: 1) Cleanser, 2) Toner, 3) Serum, 4) Eye cream, 5) Moisturizer, 6) Sunscreen (morning only). Always apply sunscreen as your last step in the morning.'
        ],
        [
            'question' => 'How often should I exfoliate?',
            'answer' => 'It depends on your skin type. Normal to oily skin can exfoliate 2-3 times per week, while dry or sensitive skin should exfoliate 1-2 times per week. Always be gentle and avoid over-exfoliating, which can damage your skin barrier.'
        ],
        [
            'question' => 'Do I really need sunscreen every day?',
            'answer' => 'Yes! Sunscreen is the most important anti-aging product you can use. UV rays penetrate through clouds and windows, causing premature aging and skin damage even on cloudy days or indoors. Apply SPF 30+ every morning, rain or shine.'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dermaluxe | FAQs</title>
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
      margin-bottom: 2rem;
    }

    .search-faq {
      max-width: 600px;
      margin: 0 auto;
      position: relative;
    }

    .search-faq input {
      width: 100%;
      padding: 15px 20px;
      border: none;
      border-radius: var(--radius);
      font-size: 1.1rem;
      box-shadow: var(--shadow-hover);
    }

    /* Category Tabs */
    .category-tabs {
      background: white;
      padding: 2rem 0;
      margin: 2rem 0;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      position: sticky;
      top: 80px;
      z-index: 100;
    }

    .tabs-container {
      display: flex;
      gap: 1rem;
      overflow-x: auto;
      padding: 0 1rem;
      scrollbar-width: thin;
    }

    .tab-button {
      padding: 12px 24px;
      background: var(--bg-light);
      border: 2px solid var(--border-color);
      border-radius: 25px;
      color: var(--text-dark);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      white-space: nowrap;
      flex-shrink: 0;
    }

    .tab-button:hover,
    .tab-button.active {
      background: var(--red-side);
      color: white;
      border-color: var(--red-side);
      transform: translateY(-2px);
    }

    /* FAQ Section */
    .faq-section {
      padding: 3rem 0;
    }

    .category-section {
      margin-bottom: 4rem;
    }

    .category-title {
      font-size: 2rem;
      color: var(--red-side);
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 3px solid var(--border-color);
    }

    .faq-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .faq-item {
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: var(--transition);
    }

    .faq-item:hover {
      box-shadow: var(--shadow-hover);
    }

    .faq-question {
      padding: 1.5rem;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      transition: var(--transition);
    }

    .faq-question:hover {
      background: var(--bg-light);
    }

    .faq-question h3 {
      font-size: 1.2rem;
      color: var(--text-dark);
      font-weight: 600;
      flex: 1;
    }

    .faq-icon {
      font-size: 1.5rem;
      color: var(--red-side);
      transition: var(--transition);
      flex-shrink: 0;
    }

    .faq-item.active .faq-icon {
      transform: rotate(45deg);
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
    }

    .faq-answer-content {
      padding: 0 1.5rem 1.5rem;
      color: var(--text-light);
      font-size: 1.05rem;
      line-height: 1.8;
    }

    .faq-item.active .faq-answer {
      max-height: 500px;
    }

    /* Contact CTA */
    .contact-cta {
      background: white;
      padding: 3rem;
      border-radius: var(--radius);
      text-align: center;
      margin: 3rem 0;
      box-shadow: var(--shadow);
    }

    .contact-cta h2 {
      font-size: 2rem;
      color: var(--text-dark);
      margin-bottom: 1rem;
    }

    .contact-cta p {
      color: var(--text-light);
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    .cta-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn {
      padding: 12px 32px;
      border-radius: var(--radius);
      font-weight: 600;
      text-decoration: none;
      transition: var(--transition);
      display: inline-block;
    }

    .btn-primary {
      background: var(--red-side);
      color: white;
      border: 2px solid var(--red-side);
    }

    .btn-primary:hover {
      background: #A63E28;
      border-color: #A63E28;
      transform: translateY(-2px);
      box-shadow: var(--shadow-hover);
    }

    .btn-outline {
      background: white;
      color: var(--red-side);
      border: 2px solid var(--red-side);
    }

    .btn-outline:hover {
      background: var(--red-side);
      color: white;
    }

    /* Footer */
    .footer {
      background: var(--text-dark);
      color: white;
      padding: 3rem 0 1.5rem;
      margin-top: 4rem;
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

    .no-results {
      text-align: center;
      padding: 3rem;
      color: var(--text-light);
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2rem;
      }

      .nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }

      .category-tabs {
        position: static;
      }

      .cta-buttons {
        flex-direction: column;
        align-items: stretch;
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
        <a href="contact.php">Contact Us</a>
        <a href="store-locator.php">Stores</a>
        <a href="faqs.php" class="active">FAQs</a>
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
      <h1>Frequently Asked Questions</h1>
      <p>Find answers to common questions about our products and services</p>
      <div class="search-faq">
        <input type="text" id="searchInput" placeholder="Search for answers..." oninput="searchFAQ()">
      </div>
    </div>
  </section>

  <div class="category-tabs">
    <div class="container">
      <div class="tabs-container">
        <?php foreach (array_keys($faq_categories) as $category): ?>
          <button class="tab-button" onclick="scrollToCategory('<?php echo strtolower(str_replace(' ', '-', $category)); ?>')">
            <?php echo $category; ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <section class="faq-section">
    <div class="container">
      <div id="faqContent">
        <?php foreach ($faq_categories as $category => $faqs): ?>
          <div class="category-section" id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
            <h2 class="category-title"><?php echo $category; ?></h2>
            <div class="faq-list">
              <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item" data-question="<?php echo strtolower($faq['question']); ?>" data-answer="<?php echo strtolower($faq['answer']); ?>">
                  <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                    <span class="faq-icon">+</span>
                  </div>
                  <div class="faq-answer">
                    <div class="faq-answer-content">
                      <?php echo htmlspecialchars($faq['answer']); ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        
        <div id="noResults" class="no-results" style="display: none;">
          <h3>No results found</h3>
          <p>Try different keywords or browse our FAQ categories above</p>
        </div>
      </div>
    </div>
  </section>

  <section class="container">
    <div class="contact-cta">
      <h2>Still Have Questions?</h2>
      <p>Can't find what you're looking for? Our team is here to help!</p>
      <div class="cta-buttons">
        <a href="contact.php" class="btn btn-primary">Contact Support</a>
        <a href="store-locator.php" class="btn btn-outline">Visit a Store</a>
        <a href="tel:+639123456789" class="btn btn-outline">Call Us</a>
      </div>
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
          <h3>HELP</h3>
          <ul>
            <li><a href="faqs.php">FAQs</a></li>
            <li><a href="support.php">Support Center</a></li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; 2025 Dermaluxe. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    function toggleFAQ(element) {
      const faqItem = element.closest('.faq-item');
      const allItems = document.querySelectorAll('.faq-item');
      
      // Close other items
      allItems.forEach(item => {
        if (item !== faqItem && item.classList.contains('active')) {
          item.classList.remove('active');
        }
      });
      
      // Toggle current item
      faqItem.classList.toggle('active');
    }

    function scrollToCategory(categoryId) {
      const element = document.getElementById(categoryId);
      if (element) {
        const offset = 150;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - offset;
        
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    }

    function searchFAQ() {
      const searchTerm = document.getElementById('searchInput').value.toLowerCase();
      const faqItems = document.querySelectorAll('.faq-item');
      const categorySections = document.querySelectorAll('.category-section');
      const noResults = document.getElementById('noResults');
      let hasResults = false;

      if (searchTerm === '') {
        // Show all items and categories
        faqItems.forEach(item => {
          item.style.display = 'block';
        });
        categorySections.forEach(section => {
          section.style.display = 'block';
        });
        noResults.style.display = 'none';
        return;
      }

      // Search in questions and answers
      faqItems.forEach(item => {
        const question = item.getAttribute('data-question');
        const answer = item.getAttribute('data-answer');
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
          item.style.display = 'block';
          hasResults = true;
        } else {
          item.style.display = 'none';
        }
      });

      // Hide categories with no visible items
      categorySections.forEach(section => {
        const visibleItems = section.querySelectorAll('.faq-item[style="display: block;"]');
        if (visibleItems.length === 0) {
          section.style.display = 'none';
        } else {
          section.style.display = 'block';
        }
      });

      noResults.style.display = hasResults ? 'none' : 'block';
    }

    // Highlight active tab on scroll
    window.addEventListener('scroll', () => {
      const tabs = document.querySelectorAll('.tab-button');
      const sections = document.querySelectorAll('.category-section');
      
      let currentSection = '';
      
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 200;
        if (window.pageYOffset >= sectionTop) {
          currentSection = section.getAttribute('id');
        }
      });

      tabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.textContent.toLowerCase().replace(' ', '-') === currentSection) {
          tab.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>