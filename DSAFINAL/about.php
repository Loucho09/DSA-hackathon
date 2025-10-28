<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dermaluxe | About Us</title>
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
      line-height: 1.8;
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

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, rgba(200, 75, 49, 0.9), rgba(166, 62, 40, 0.9)),
                  url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23FFF8F5" width="1200" height="600"/></svg>');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 6rem 0;
      text-align: center;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      animation: fadeInUp 1s ease;
    }

    .hero p {
      font-size: 1.3rem;
      max-width: 800px;
      margin: 0 auto;
      opacity: 0.95;
      animation: fadeInUp 1s ease 0.2s backwards;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Story Section */
    .story-section {
      padding: 5rem 0;
    }

    .story-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
      margin-bottom: 4rem;
    }

    .story-text h2 {
      font-size: 2.5rem;
      color: var(--red-side);
      margin-bottom: 1.5rem;
      font-weight: 700;
    }

    .story-text p {
      font-size: 1.1rem;
      color: var(--text-light);
      margin-bottom: 1rem;
    }

    .story-image {
      width: 100%;
      height: 400px;
      background: var(--border-color);
      border-radius: var(--radius);
      overflow: hidden;
    }

    .story-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Values Section */
    .values-section {
      background: white;
      padding: 5rem 0;
    }

    .section-title {
      text-align: center;
      font-size: 2.5rem;
      color: var(--red-side);
      margin-bottom: 3rem;
      font-weight: 700;
    }

    .values-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
    }

    .value-card {
      background: var(--bg-light);
      padding: 2.5rem;
      border-radius: var(--radius);
      text-align: center;
      transition: var(--transition);
      border: 2px solid transparent;
    }

    .value-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
      border-color: var(--red-side);
    }

    .value-icon {
      width: 80px;
      height: 80px;
      background: var(--red-side);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin: 0 auto 1.5rem;
      transition: var(--transition);
    }

    .value-card:hover .value-icon {
      transform: scale(1.1) rotate(5deg);
    }

    .value-card h3 {
      font-size: 1.5rem;
      color: var(--text-dark);
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .value-card p {
      color: var(--text-light);
      font-size: 1rem;
    }

    /* Team Section */
    .team-section {
      padding: 5rem 0;
    }

    .team-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .team-member {
      background: white;
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      text-align: center;
    }

    .team-member:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .team-photo {
      width: 100%;
      height: 300px;
      background: var(--border-color);
      overflow: hidden;
    }

    .team-photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .team-info {
      padding: 1.5rem;
    }

    .team-info h4 {
      font-size: 1.3rem;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .team-info .role {
      color: var(--red-side);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .team-info p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    /* Stats Section */
    .stats-section {
      background: linear-gradient(135deg, var(--red-side), #A63E28);
      color: white;
      padding: 4rem 0;
      margin: 5rem 0;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 3rem;
      text-align: center;
    }

    .stat-item h3 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .stat-item p {
      font-size: 1.1rem;
      opacity: 0.9;
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

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }

      .story-content {
        grid-template-columns: 1fr;
        gap: 2rem;
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
        <a href="shopall.php
        
        
        
        ">Shop</a>
        <a href="about.php" class="active">About Us</a>
        <a href="contact.php">Contact Us</a>
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
      <h1>Our Story</h1>
      <p>Committed to revolutionizing skincare through science, nature, and personalized care</p>
    </div>
  </section>

  <section class="story-section">
    <div class="container">
      <div class="story-content">
        <div class="story-text">
          <h2>Who We Are</h2>
          <p>Founded in 2020, Dermaluxe emerged from a simple belief: everyone deserves access to premium skincare that truly works. Our journey began when our founder, a dermatologist with over 15 years of experience, noticed a gap in the market for effective, science-backed skincare that was also accessible and personalized.</p>
          <p>We combine cutting-edge dermatological research with natural ingredients to create products that deliver real results. Every formula is meticulously crafted and rigorously tested to ensure safety and efficacy.</p>
        </div>
        <div class="story-image">
          <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 400'><rect fill='%23E8D5CF' width='600' height='400'/><text x='50%' y='50%' text-anchor='middle' fill='%23C84B31' font-size='24' font-family='Arial'>Dermaluxe Lab</text></svg>" alt="Our Lab">
        </div>
      </div>

      <div class="story-content" style="direction: rtl;">
        <div class="story-text" style="direction: ltr;">
          <h2>Our Mission</h2>
          <p>To empower individuals on their skincare journey by providing personalized, scientifically-proven solutions that enhance natural beauty and promote skin health.</p>
          <p>We believe that great skin is the foundation of confidence. Our mission extends beyond selling products – we're committed to educating our community about proper skincare practices and helping each person discover what works best for their unique skin.</p>
        </div>
        <div class="story-image">
          <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 400'><rect fill='%23E8D5CF' width='600' height='400'/><text x='50%' y='50%' text-anchor='middle' fill='%23C84B31' font-size='24' font-family='Arial'>Our Mission</text></svg>" alt="Our Mission">
        </div>
      </div>
    </div>
  </section>

  <section class="values-section">
    <div class="container">
      <h2 class="section-title">Our Core Values</h2>
      <div class="values-grid">
        <div class="value-card">
          <div class="value-icon">🔬</div>
          <h3>Science-Backed</h3>
          <p>Every product is formulated based on dermatological research and clinical studies, ensuring proven effectiveness.</p>
        </div>

        <div class="value-card">
          <div class="value-icon">🌿</div>
          <h3>Natural Ingredients</h3>
          <p>We harness the power of nature, using high-quality botanical extracts and sustainable sourcing practices.</p>
        </div>

        <div class="value-card">
          <div class="value-icon">✨</div>
          <h3>Personalized Care</h3>
          <p>Your skin is unique. We offer tailored recommendations and customized routines for your specific needs.</p>
        </div>

        <div class="value-card">
          <div class="value-icon">🤝</div>
          <h3>Transparency</h3>
          <p>Complete ingredient disclosure and honest communication about what our products can and cannot do.</p>
        </div>

        <div class="value-card">
          <div class="value-icon">🌍</div>
          <h3>Sustainability</h3>
          <p>Committed to eco-friendly practices, from ingredient sourcing to recyclable packaging.</p>
        </div>

        <div class="value-card">
          <div class="value-icon">💚</div>
          <h3>Cruelty-Free</h3>
          <p>Never tested on animals. All our products are certified cruelty-free and vegan-friendly.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="stats-section">
    <div class="container">
      <div class="stats-grid">
        <div class="stat-item">
          <h3>50K+</h3>
          <p>Happy Customers</p>
        </div>
        <div class="stat-item">
          <h3>100+</h3>
          <p>Products</p>
        </div>
        <div class="stat-item">
          <h3>15+</h3>
          <p>Expert Dermatologists</p>
        </div>
        <div class="stat-item">
          <h3>98%</h3>
          <p>Satisfaction Rate</p>
        </div>
      </div>
    </div>
  </section>

  <!-- <section class="team-section">
    <div class="container">
      <h2 class="section-title">Meet Our Team</h2>
      <div class="team-grid">
        <div class="team-member">
          <div class="team-photo">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'><rect fill='%23E8D5CF' width='300' height='300'/><circle cx='150' cy='120' r='50' fill='%23C84B31'/><circle cx='150' cy='200' r='70' fill='%23C84B31'/></svg>" alt="Dr. Sarah Chen">
          </div>
          <div class="team-info">
            <h4>Dr. Sarah Chen</h4>
            <p class="role">Founder & Chief Dermatologist</p>
            <p>Board-certified dermatologist with 15+ years of experience in cosmetic and clinical dermatology.</p>
          </div>
        </div>

        <div class="team-member">
          <div class="team-photo">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'><rect fill='%23E8D5CF' width='300' height='300'/><circle cx='150' cy='120' r='50' fill='%23C84B31'/><circle cx='150' cy='200' r='70' fill='%23C84B31'/></svg>" alt="Michael Rodriguez">
          </div>
          <div class="team-info">
            <h4>Michael Rodriguez</h4>
            <p class="role">Chief Product Officer</p>
            <p>Cosmetic chemist specializing in innovative skincare formulations and sustainable ingredients.</p>
          </div>
        </div>

        <div class="team-member">
          <div class="team-photo">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'><rect fill='%23E8D5CF' width='300' height='300'/><circle cx='150' cy='120' r='50' fill='%23C84B31'/><circle cx='150' cy='200' r='70' fill='%23C84B31'/></svg>" alt="Emily Watson">
          </div>
          <div class="team-info">
            <h4>Emily Watson</h4>
            <p class="role">Head of Customer Experience</p>
            <p>Dedicated to ensuring every customer receives personalized care and achieves their skincare goals.</p>
          </div>
        </div>

        <div class="team-member">
          <div class="team-photo">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 300'><rect fill='%23E8D5CF' width='300' height='300'/><circle cx='150' cy='120' r='50' fill='%23C84B31'/><circle cx='150' cy='200' r='70' fill='%23C84B31'/></svg>" alt="Dr. James Park">
          </div>
          <div class="team-info">
            <h4>Dr. James Park</h4>
            <p class="role">Research Director</p>
            <p>Leading our clinical research initiatives and ensuring all products meet the highest standards.</p>
          </div>
        </div>
      </div>
    </div>
  </section> -->

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
          <h3>CONNECT WITH US</h3>
          <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Follow us on social media for skincare tips and exclusive offers!</p>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; 2025 Dermaluxe. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    // Animate stats on scroll
    const observerOptions = {
      threshold: 0.5,
      rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const stats = entry.target.querySelectorAll('.stat-item h3');
          stats.forEach(stat => {
            const finalValue = stat.textContent;
            const numValue = parseInt(finalValue.replace(/[^0-9]/g, ''));
            const suffix = finalValue.replace(/[0-9]/g, '');
            
            let current = 0;
            const increment = numValue / 50;
            const timer = setInterval(() => {
              current += increment;
              if (current >= numValue) {
                stat.textContent = finalValue;
                clearInterval(timer);
              } else {
                stat.textContent = Math.floor(current) + suffix;
              }
            }, 30);
          });
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
      observer.observe(statsSection);
    }
  </script>
</body>
</html>