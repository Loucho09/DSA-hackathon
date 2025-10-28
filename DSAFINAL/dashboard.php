<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Align with schema using prepared statements; fallback-safe fields
$user = [
    'full_name' => 'User',
    'image' => ''
];
if ($stmt = $conn->prepare('SELECT full_name, image FROM users WHERE id = ? LIMIT 1')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($fullName, $image);
    if ($stmt->fetch()) {
        $user['full_name'] = $fullName ?: 'User';
        $user['image'] = $image ?: '';
    }
    $stmt->close();
}

// Handle post submission (optional functionality retained)
if (isset($_POST['content']) && is_string($_POST['content'])) {
    $content = $_POST['content'];
    if ($stmt = $conn->prepare('INSERT INTO posts (user_id, content) VALUES (?, ?)')) {
        $stmt->bind_param('is', $user_id, $content);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle image upload (optional functionality retained)
if (isset($_FILES['image']) && is_array($_FILES['image'])) {
    $targetDir = __DIR__ . '/uploads/';
    if (!is_dir($targetDir)) { @mkdir($targetDir, 0777, true); }
    $basename = basename($_FILES['image']['name']);
    $target = $targetDir . $basename;
    if (!empty($basename) && move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        if ($stmt = $conn->prepare('UPDATE users SET image = ? WHERE id = ?')) {
            $stmt->bind_param('si', $basename, $user_id);
            $stmt->execute();
            $stmt->close();
        }
        $user['image'] = $basename;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
        
</head>
<body>

     <div class="overlay" id="overlay" onclick="toggleMenu()"></div>
     
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">YOUR LOGO</div>
            <nav>
                <ul>
                    <li>Dashboard</li>
                    <li button class="back-btn" onclick="window.location.href='shopall.php'">Products</li>
                    <li><a id="logoutMenuItem" href="logout.php" style="display:none; text-decoration:none; color:inherit;">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <!-- Mat -- Added Help functionality -->
        <main class="main-content">
            <header class="topbar">
                <button class="menu-btn" onclick="toggleMenu()">☰ Menu</button>   
                <input type="text" placeholder="Search..." class="search-box" />
                <div class="header-links">
                    <a id="registerLink" href="register.php">Register</a>
                    <a id="signInLink" href="login.php">Sign in</a>
                    <a href="#" id="helpLink">Help</a>
                    <a href="cart.php">Cart</a>
                </div>
            </header>

            <div class="banner">
                <h2>Discover Great Deals Every Day!</h2>
                <p>Sunscreens, Lotions, Cleansers, and More!</p>
            </div>

        <section class="hero">
            <div class="hero-content">
                <h1>Check Out Our Skin Products! </h1>
                <p>Skin Whitening. Retaining, and More!</p>
                <p class="price">Starting at ₱138.00</p>
                <button class="shop-now-btn" onclick="window.location.href='shopall.php'">Shop Now</button>
            </div>
            <div class="hero-image">
                Product Showcase Image
            </div>
        </section>
    </div>


        
      <!-- Exclusive Deals Section -->
            <section class="exclusive-deals">
                <h2>🔥 Exclusive Deals - Limited Time Only!</h2>
                <div class="deals-grid">
                    <div class="deal-card">
                        <div class="deal-badge">50% OFF</div>
                        <div class="deal-title">Summer Bundle Pack</div>
                        <div class="deal-description">Get 3 sunscreens + 1 free lip balm</div>
                        <div class="deal-price">₱149.99</div>
                        <button class="deal-btn">Grab Deal</button>
                    </div>

                    <div class="deal-card">
                        <div class="deal-badge">BUY 2 GET 1</div>
                        <div class="deal-title">Cleanser Special</div>
                        <div class="deal-description">Buy any 2 cleansers, get 1 free</div>
                        <div class="deal-price">₱59.99</div>
                        <button class="deal-btn">Shop Now</button>
                    </div>

                    <div class="deal-card">
                        <div class="deal-badge">30% OFF</div>
                        <div class="deal-title">Lotion Mega Sale</div>
                        <div class="deal-description">All body lotions on discount</div>
                        <div class="deal-price">From ₱119.99</div>
                        <button class="deal-btn">View Deals</button>
                    </div>
                </div>
            </section>

    <footer class="footer">
                <div class="footer-content">
                    <div class="footer-section">
                        <div class="footer-logo">SKINCARE SHOP</div>
                        <p>Your trusted destination for premium skincare products. We bring you the best in beauty and wellness.</p>
                        <div class="social-links">
                            <a href="#">f</a>
                            <a href="#">t</a>
                            <a href="#">i</a>
                            <a href="#">y</a>
                        </div>
                    </div>

                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Shop</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3>Customer Service</h3>
                        <ul>
                            <li><a href="#">Shipping Information</a></li>
                            <li><a href="#">Returns & Exchanges</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Track Order</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3>Contact Us</h3>
                        <p>📧 Email: support@skincareshop.com</p>
                        <p>📧 Sales: sales@skincareshop.com</p>
                        <p>📞 Phone: +1 (555) 123-4567</p>
                        <p>📍 Address: 123 Beauty Street, Wellness City, WC 12345</p>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; 2025 Skincare Shop. All rights reserved.</p>
                </div>
            </footer>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            sidebar.classList.toggle('menu-open');
            overlay.classList.toggle('active');
        }

        const buttons = document.querySelectorAll(".categories button");
        const products = document.querySelectorAll(".product");

        buttons.forEach(button => {
            button.addEventListener("click", () => {
                // Remove active class from all buttons
                buttons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');

                const id = button.id.toLowerCase(); 
                let category = id.replace("btn", ""); 

                products.forEach(product => {
                    if (id === "all") {
                        product.style.display = "block";
                    } else {
                        if (product.dataset.category === category) {
                            product.style.display = "block";
                        } else {
                            product.style.display = "none";
                        }
                    }
                });
            });
        });

        // Since we're authenticated via PHP session, show Logout and hide Register/Sign in
        (function authUiFromSession() {
            const logoutItem = document.getElementById('logoutMenuItem');
            const registerLink = document.getElementById('registerLink');
            const signInLink = document.getElementById('signInLink');
            if (logoutItem) logoutItem.style.display = 'list-item';
            if (registerLink) registerLink.style.display = 'none';
            if (signInLink) signInLink.style.display = 'none';
            // Optionally sync localStorage for other pages that rely on it
            try { localStorage.setItem('auth', 'true'); } catch (e) {}
        })();
        // Help overlay open/close and submit
        (function setupHelpOverlay(){
            const link = document.getElementById('helpLink');
            if (!link) return;
            let modal = document.getElementById('helpModal');
            let backdrop = document.getElementById('helpBackdrop');
            if (!modal) {
                backdrop = document.createElement('div');
                backdrop.id = 'helpBackdrop';
                document.body.appendChild(backdrop);
                modal = document.createElement('div');
                modal.id = 'helpModal';
                modal.innerHTML = `
                    <h3>Contact Support</h3>
                    <p>Tell us about your issue and we’ll get back to you via email.</p>
                    <form id="helpForm">
                        <div class="row">
                            <div>
                                <label for="helpName">Your Name</label>
                                <input type="text" id="helpName" name="name" required>
                            </div>
                            <div>
                                <label for="helpEmail">Your Email</label>
                                <input type="email" id="helpEmail" name="email" required>
                            </div>
                        </div>
                        <div style="margin-top:10px">
                            <label for="helpMessage">Message</label>
                            <textarea id="helpMessage" name="message" required></textarea>
                        </div>
                        <div id="helpStatus" style="margin-top:8px; font-size:14px;"></div>
                        <div class="actions">
                            <button type="button" id="helpClose" class="btn">Close</button>
                            <button type="submit" class="btn">Send</button>
                        </div>
                    </form>`;
                document.body.appendChild(modal);
            }
            const open = ()=>{ modal.classList.add('open'); backdrop.classList.add('open'); };
            const close = ()=>{ modal.classList.remove('open'); backdrop.classList.remove('open'); };
            link.addEventListener('click', (e)=>{ e.preventDefault(); open(); });
            document.addEventListener('click', (e)=>{
                if (e.target && (e.target.id === 'helpClose' || e.target.id === 'helpBackdrop')) close();
            });
            document.addEventListener('submit', async (e)=>{
                const form = e.target;
                if (form && form.id === 'helpForm') {
                    e.preventDefault();
                    const status = document.getElementById('helpStatus');
                    status.textContent = '';
                    try {
                        const data = new FormData(form);
                        const res = await fetch('support.php', { method: 'POST', body: data });
                        const json = await res.json().catch(()=>null);
                        if (json && json.ok) {
                            status.style.color = '#0b6b2b';
                            status.textContent = 'Thanks! Your message has been sent.';
                            form.reset();
                        } else {
                            status.style.color = '#a24646';
                            status.textContent = (json && json.error) ? json.error : 'Failed to send. Please try again later.';
                        }
                    } catch(err) {
                        const status = document.getElementById('helpStatus');
                        status.style.color = '#a24646';
                        status.textContent = 'Network error. Please try again.';
                    }
                }
            });
        })();
    </script>
 
<style>
    * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #fef2f2;
            color: #333;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #fff;
            padding: 20px;
            border-right: 1px solid #eee;
            height: 100%;
            overflow-y: auto;
            position: fixed;
            left: -220px;
            top: 0;
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .sidebar.menu-open {
            left: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
        }

        .logo {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 1.2em;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            padding: 10px;
            cursor: pointer;
            border-radius: 8px;
            color: #000000;
            transition: background 0.3s;
        }

        .sidebar li:hover {
            background: #793838;
            color: #fff;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px 50px;
            border-bottom: 1px solid #eee;
        }

        .search-box {
            width: 500px;
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .menu-btn {
            background-color: #a24646;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s;
        }

        .menu-btn:hover {
            background-color: #8a3939;
        }

        .header-links {
            display: flex;
            gap: 20px;
        }

        .header-links a {
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }

        .header-links a:hover {
            text-decoration: underline;
        }        
        
        .banner {
            background: linear-gradient(135deg, #5a2222 0%, #9f6161 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .banner h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

       
        /* Menu overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        /* Main content adjustment */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            width: 100%;
        }

         /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #e8d5d5 0%, #f5f5f5 100%);
            padding: 80px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 500px;
        }

        .hero-content {
            max-width: 500px;
        }

        .hero h1 {
            font-size: 48px;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #000;
        }

        .hero p {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        .hero .price {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }

        .shop-now-btn {
            background: #000;
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .shop-now-btn:hover {
            background: #b43b3b;
        }

        .hero-image {
            width: 600px;
            height: 400px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #999;
        }

        /* Dashboard View (Hidden by default) */
        .dashboard {
            display: none;
            min-height: 100vh;
        }

        .dashboard.active {
            display: flex;
        }


        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -220px;
                transition: left 0.3s;
                z-index: 1000;
            }

            .sidebar.active {
                left: 0;
            }

            .topbar {
                padding: 15px 20px;
            }

            .search-box {
                width: 200px;
            }

            .header-links {
                display: none;
            }
        }



          /* Exclusive Deals Section */
        .exclusive-deals {
            background: linear-gradient(135deg, #8a3939 0%, #b43b3b 100%);
            color: white;
            padding: 60px 40px;
            margin: 60px 0;
        }

        .exclusive-deals h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 40px;
        }

        .deals-grid {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .deal-card {
            background: white;
            color: #333;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s;
        }

        .deal-card:hover {
            transform: translateY(-8px);
        }

        .deal-badge {
            background: #ff4444;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 14px;
        }

        .deal-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .deal-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .deal-price {
            font-size: 28px;
            font-weight: bold;
            color: #b43b3b;
            margin-bottom: 20px;
        }

        .deal-btn {
            background: #b43b3b;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .deal-btn:hover {
            background: #8a3030;
        }

         /* Footer */
          .footer {
            background: #2a2a2a;
            color: #fff;
            padding: 60px 40px 30px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #b43b3b;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .footer-section p {
            line-height: 1.8;
            color: #ccc;
            margin-bottom: 15px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section li {
            margin-bottom: 12px;
        }

        .footer-section a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #b43b3b;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: #444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }

        .social-links a:hover {
            background: #b43b3b;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #444;
            color: #999;
        }
        
        #helpBackdrop { 
            position: fixed; 
            inset: 0; 
            background: rgba(0,0,0,0.5); 
            opacity: 0; 
            pointer-events: none; 
            transition: opacity .2s ease; 
        }

        #helpBackdrop.open { 
            opacity: 1; 
            pointer-events: auto; 
        }

        #helpModal { 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%) scale(.98); 
            width: 95%; 
            max-width: 520px; 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
            padding: 20px; 
            opacity: 0; 
            pointer-events: none; 
            transition: opacity .2s ease, transform .2s ease; 
            z-index: 1100; 
        }

        #helpModal.open { 
            opacity: 1; 
            pointer-events: auto; 
            transform: translate(-50%, -50%) scale(1); 
        }

        #helpModal h3 {
            margin-bottom: 10px; 
        }

        #helpModal p { 
            margin-bottom: 12px; 
            color: #555; 
        }

        #helpModal .row { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 10px; 
        }

        #helpModal input, #helpModal textarea { 
            width: 100%; 
            padding: 10px 12px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            font-family: inherit; 
        }

        #helpModal textarea { 
            min-height: 120px; 
            resize: vertical; 
        }

        #helpModal .actions { 
            display: flex; 
            justify-content: flex-end; 
            gap: 10px; 
            margin-top: 12px; 
        }

        #helpModal .btn { 
            background: #a24646; 
            color: #fff; border: none; 
            padding: 10px 14px; 
            border-radius: 8px; 
            cursor: pointer; 
        }

        #helpClose { 
            background: transparent; 
            color: #a24646; 
            border: 1px solid #a24646; 
        }
</style>

</body>
</html>
