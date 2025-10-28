<?php
session_start();
$isAuthed = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Skincare Products</title>
</head>
<body>

 <div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<div class="container">
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="logo">YOUR LOGO</div>
    <nav>
      <ul>
        <li button class="back-btn" onclick="window.location.href='dashboard.php'">Dashboard</li>
        <li>Products</li>
        <li><a href="logout.php" style="text-decoration:none; color:inherit;">Logout</a></li>
      </ul>
    </nav>
    
  </aside>

   <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <button class="menu-btn" onclick="toggleMenu()">☰ Menu</button>   
                <input type="text" placeholder="Search..." class="search-box" />
                <div class="header-links">
<?php if ($isAuthed): ?>
                    <a href="logout.php">Logout</a>
<?php else: ?>
                    <a href="register.php">Register</a>
                    <a href="login.php">Sign in</a>
<?php endif; ?>
                    <a href="#">Help</a>
                    <a href="#">Cart</a>
                </div>
            </header>

    <h1>All our Skincare Products</h1>
    <p>Here are all our products, all in one place</p>

    <div class="categories">
      <button id="all">All Products</button>
      <button id="sunscreenBtn">Sunscreens</button>
      <button id="lipcareBtn">Lip Care</button>
      <button id="cleanserBtn">Cleanser</button>
      <button id="lotionBtn">Lotion</button>
    </div>

    <div class="product-row">

      <!-- Sunscreens -->
      <div class="product" data-category="sunscreen">
        <div class="product-img-container">
          <img src="Nivea.png.png" alt="Product 1">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Nivea Sun Protection & Hydration Sunscreen</h3>
        <p class="price">₱758.00</p>
      </div>

      <div class="product" data-category="sunscreen">
        <div class="product-img-container">
          <img src="CETAPHIL_SUNSCREEN_SPF30_Product.png" alt="Product 2">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Cetaphil Sunscreen SPF30</h3>
        <p class="price">₱1,362.00</p>
      </div>

      <div class="product" data-category="sunscreen">
        <div class="product-img-container">
          <img src="BELOSUNSCREEN.png.png" alt="Product 3">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Belo SunExpert Whitening Sunscreen</h3>
        <p class="price">₱495.00</p>
      </div>

      <!-- Lip Care -->
      <div class="product" data-category="lipcare">
        <div class="product-img-container">
          <img src="vaseline.png" alt="Product 4">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Vaseline Lip Therapy Rosy Tinted Lip Balm Tube 10G</h3>
        <p class="price">₱184.00</p>
      </div>

      <div class="product" data-category="lipcare">
        <div class="product-img-container">
          <img src="niveabalm.png" alt="Product 5">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Nivea Cherry Lip Care</h3>
        <p class="price">₱699.00</p>
      </div>

      <div class="product" data-category="lipcare">
        <div class="product-img-container">
          <img src="lipcaremellow.png" alt="Product 6">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Vaseline Lip Care Mellow Rose</h3>
        <p class="price">₱138.00</p>
      </div>

      <!-- Cleansers -->
      <div class="product" data-category="cleanser">
        <div class="product-img-container">
          <img src="cosrxgoodmorning.png" alt="Product 7">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>COSRX Low pH Good Morning Cleanser</h3>
        <p class="price">₱339.00</p>
      </div>

      <div class="product" data-category="cleanser">
        <div class="product-img-container">
          <img src="CetaphilCleanser.png" alt="Product 8">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Cetaphil Gentle Skin Cleanser</h3>
        <p class="price">₱274.00</p>
      </div>

      <div class="product" data-category="cleanser">
        <div class="product-img-container">
          <img src="luxeOrganic.png" alt="Product 9">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Luxe Organic Cica Rescue Cleanser</h3>
        <p class="price">₱289.00</p>
      </div>

      <!-- Lotions -->
      <div class="product" data-category="lotion">
        <div class="product-img-container">
          <img src="aveenolotion.png" alt="Product 10">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Aveeno Daily Moitsturizing Body Lotion</h3>
        <p class="price">₱349.00</p>
      </div>

      <div class="product" data-category="lotion">
        <div class="product-img-container">
          <img src="Cetaphil-Lotion.png" alt="Product 11">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Cetaphil Moisturizing Lotion</h3>
        <p class="price">₱929.00</p>
      </div>

      <div class="product" data-category="lotion">
        <div class="product-img-container">
          <img src="belolotion.png" alt="Product 12">
          <button class="cart-btn"><img src="shopping-cart.png" alt="Cart"></button>
        </div>
        <h3>Belo Intensive Whitening Lotion</h3>
        <p class="price">₱300.00</p>
      </div>

    </div>
  </main>
</div>

<!-- CSS -->
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
}

.sidebar li:hover {
  background: #793838;
  color: #000000;
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
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

.main-content h1, .main-content p {
  text-align: center;
  margin-top: 20px;
}

.main-content h1 {
  font-size: 35px;
}
.main-content p {
  font-size: 22px;
}

.categories{
  display: flex;
  justify-content: center;
  gap: 13px;
  margin-top: 15px;
  margin-bottom: 30px;
}

.categories button {
  background-color: #ffffff;      
  color: #b43b3b;                  
  border: 2px solid #b43b3b;       
  padding: 10px 22px;
  border-radius: 50px;            
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
}

.categories button:hover {
  background-color: #b43b3b;
  color: white;
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.product-row {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 30px;
  flex-wrap: wrap;
  margin-top: 30px;
  padding-bottom: 40px;
}

.product {
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  text-align: center;
  width: 200px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  height: 320px;
}

.product h3 {
  margin-top: 10px;
  font-size: 16px;
  color: #333;
}

.product:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

.product-img-container {
  position: relative;
  width: 100%;
  height: 180px;
}

.product-img-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.price {
  color: #b43b3b;
  font-weight: 600;
  margin-top: auto;
}

.cart-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 40px;
  height: 40px;
  background-color: #b43b3b;
  border: none;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s ease, transform 0.3s ease;
  cursor: pointer;
}

.cart-btn img {
  width: 20px;
  height: 20px;
}

.product-img-container:hover .cart-btn {
  opacity: 1;
  transform: scale(1.1);
}

.product h3 {
  margin-top: 10px;

}
</style>

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
    </script>
 

</body>
</html>


