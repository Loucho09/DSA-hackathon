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

// Handle support ticket submission
$ticket_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    $subject = $_POST['subject'] ?? '';
    $category = $_POST['category'] ?? '';
    $message = $_POST['message'] ?? '';
    $priority = $_POST['priority'] ?? 'medium';
    
    if ($subject && $category && $message) {
        $ticket_number = 'TKT-' . date('Ymd') . '-' . rand(1000, 9999);
        
        if ($stmt = $conn->prepare('INSERT INTO support_tickets (user_id, ticket_number, subject, category, message, priority) VALUES (?, ?, ?, ?, ?, ?)')) {
            $stmt->bind_param('isssss', $user_id, $ticket_number, $subject, $category, $message, $priority);
            if ($stmt->execute()) {
                $ticket_message = "Ticket submitted successfully! Your ticket number is: $ticket_number";
            } else {
                $ticket_message = "Error submitting ticket. Please try again.";
            }
            $stmt->close();
        }
    } else {
        $ticket_message = "Please fill in all required fields.";
    }
}

// Fetch user's previous tickets
$previous_tickets = [];
if ($stmt = $conn->prepare('SELECT ticket_number, subject, category, status, created_at FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC LIMIT 5')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $previous_tickets[] = $row;
    }
    $stmt->close();
}

$categories = ['Product Inquiry', 'Order Issue', 'Shipping', 'Returns & Exchanges', 'Technical Issue', 'Account Issue', 'General Question'];
$priorities = [
    'low' => 'Low',
    'medium' => 'Medium', 
    'high' => 'High',
    'urgent' => 'Urgent'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dermaluxe | Support</title>
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
        <div class="user-menu">
          <img src="<?php echo $user['image'] ?: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiNGMEYwRjAiLz4KPGNpcmNsZSBjeD0iMjAiIGN5PSIxNiIgcj0iNSIgZmlsbD0iI0NFQ0VDRSIvPjxwYXRoIGQ9Ik0yOCAzMGMwLTQuNDE4LTQuNDU4LTgtMTAtOFM4IDI1LjU4MiA4IDMwIiBmaWxsPSIjQ0VDRUNFIi8+Cjwvc3ZnPgo='; ?>" 
               alt="Profile" class="user-avatar">
          <div class="dropdown">
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="skin-analysis.php">Skin Analysis</a>
            <a href="logout.php">Logout</a>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <div class="support-header">
        <h1>Customer Support</h1>
        <p>We're here to help! Get assistance with your orders, products, or account.</p>
      </div>

      <div class="support-content">
        <!-- Quick Help -->
        <div class="quick-help-section">
          <h2>Quick Help 🚀</h2>
          <div class="help-options">
            <a href="faqs.php" class="help-option">
              <div class="help-icon">❓</div>
              <h3>FAQs</h3>
              <p>Find answers to common questions</p>
            </a>
            <a href="ordering-process.php" class="help-option">
              <div class="help-icon">📦</div>
              <h3>Order Help</h3>
              <p>Track orders and shipping info</p>
            </a>
            <a href="returns.php" class="help-option">
              <div class="help-icon">🔄</div>
              <h3>Returns</h3>
              <p>Return or exchange products</p>
            </a>
            <a href="contact.php" class="help-option">
              <div class="help-icon">📞</div>
              <h3>Contact Us</h3>
              <p>Direct contact options</p>
            </a>
          </div>
        </div>

        <div class="support-grid">
          <!-- Contact Information -->
          <div class="support-section">
            <h2>Contact Information 📞</h2>
            <div class="contact-methods">
              <div class="contact-method">
                <div class="method-icon">📧</div>
                <div class="method-info">
                  <h3>Email Support</h3>
                  <p>support@dermaluxe.com</p>
                  <small>Response within 24 hours</small>
                </div>
              </div>
              <div class="contact-method">
                <div class="method-icon">📞</div>
                <div class="method-info">
                  <h3>Phone Support</h3>
                  <p>1-800-DERMALUX</p>
                  <small>Mon-Fri 9AM-6PM EST</small>
                </div>
              </div>
              <div class="contact-method">
                <div class="method-icon">💬</div>
                <div class="method-info">
                  <h3>Live Chat</h3>
                  <p>Available 24/7</p>
                  <small>Click the chat icon below</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Ticket -->
          <div class="support-section">
            <h2>Submit Support Ticket 🎫</h2>
            <?php if ($ticket_message): ?>
              <div class="message <?php echo strpos($ticket_message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo $ticket_message; ?>
              </div>
            <?php endif; ?>

            <form method="POST" class="ticket-form">
              <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" id="subject" name="subject" required placeholder="Brief description of your issue">
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="category">Category *</label>
                  <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="priority">Priority</label>
                  <select id="priority" name="priority">
                    <?php foreach ($priorities as $value => $label): ?>
                      <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" required rows="6" placeholder="Please provide detailed information about your issue..."></textarea>
              </div>

              <div class="form-actions">
                <button type="submit" name="submit_ticket" class="btn btn-primary">Submit Ticket</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Previous Tickets -->
        <?php if (!empty($previous_tickets)): ?>
          <div class="support-section">
            <h2>Your Recent Tickets 📋</h2>
            <div class="tickets-list">
              <?php foreach ($previous_tickets as $ticket): ?>
                <div class="ticket-item">
                  <div class="ticket-info">
                    <h4><?php echo htmlspecialchars($ticket['subject']); ?></h4>
                    <p>#<?php echo $ticket['ticket_number']; ?> • <?php echo $ticket['category']; ?> • <?php echo date('M j, Y', strtotime($ticket['created_at'])); ?></p>
                  </div>
                  <div class="ticket-status <?php echo strtolower($ticket['status']); ?>">
                    <?php echo $ticket['status']; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- FAQ Preview -->
        <div class="support-section">
          <h2>Common Questions 🤔</h2>
          <div class="faq-preview">
            <div class="faq-item">
              <h3>How long does shipping take?</h3>
              <p>Standard shipping takes 3-5 business days. Express shipping delivers within 1-2 business days.</p>
            </div>
            <div class="faq-item">
              <h3>What is your return policy?</h3>
              <p>We offer 30-day returns on all unused products. Contact us for a return authorization.</p>
            </div>
            <div class="faq-item">
              <h3>Can I change my order after placing it?</h3>
              <p>Orders can be modified within 1 hour of placement. Contact us immediately for changes.</p>
            </div>
          </div>
          <div class="faq-actions">
            <a href="faqs.php" class="btn btn-outline">View All FAQs</a>
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

  <!-- Live Chat Widget -->
  <div class="live-chat-widget">
    <button class="chat-toggle">💬</button>
    <div class="chat-window">
      <div class="chat-header">
        <h4>Live Chat Support</h4>
        <button class="close-chat">×</button>
      </div>
      <div class="chat-messages">
        <div class="message agent">
          <p>Hello! How can we help you today?</p>
        </div>
      </div>
      <div class="chat-input">
        <input type="text" placeholder="Type your message...">
        <button>Send</button>
      </div>
    </div>
  </div>

  <script>
    // Live chat functionality
    document.addEventListener('DOMContentLoaded', function() {
      const chatToggle = document.querySelector('.chat-toggle');
      const chatWindow = document.querySelector('.chat-window');
      const closeChat = document.querySelector('.close-chat');
      
      chatToggle.addEventListener('click', function() {
        chatWindow.classList.toggle('open');
      });
      
      closeChat.addEventListener('click', function() {
        chatWindow.classList.remove('open');
      });
    });
  </script>

  <style>
    /* Support Specific Styles */
    .support-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .support-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--text-dark);
    }

    .support-header p {
      font-size: 1.1rem;
      color: var(--text-light);
      max-width: 600px;
      margin: 0 auto;
    }

    .support-content {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .quick-help-section {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .quick-help-section h2 {
      color: var(--red-side);
      margin-bottom: 1.5rem;
      font-size: 1.5rem;
      font-weight: 600;
    }

    .help-options {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }

    .help-option {
      background: var(--bg-light);
      padding: 1.5rem;
      border-radius: var(--radius);
      text-decoration: none;
      text-align: center;
      transition: var(--transition);
      border: 2px solid transparent;
    }

    .help-option:hover {
      border-color: var(--btn-bg);
      transform: translateY(-2px);
    }

    .help-icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
    }

    .help-option h3 {
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
    }

    .help-option p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .support-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }

    .support-section {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
    }

    .support-section h2 {
      color: var(--red-side);
      margin-bottom: 1.5rem;
      font-size: 1.4rem;
      font-weight: 600;
    }

    .contact-methods {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .contact-method {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: var(--radius);
    }

    .method-icon {
      font-size: 1.5rem;
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: white;
      border-radius: 50%;
    }

    .method-info h3 {
      color: var(--text-dark);
      margin-bottom: 0.25rem;
      font-size: 1rem;
    }

    .method-info p {
      color: var(--text-dark);
      font-weight: 500;
      margin-bottom: 0.25rem;
    }

    .method-info small {
      color: var(--text-light);
      font-size: 0.8rem;
    }

    .ticket-form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group label {
      font-weight: 500;
      color: var(--text-dark);
    }

    .form-group input, .form-group select, .form-group textarea {
      padding: 10px 12px;
      border: 2px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
      outline: none;
      border-color: var(--input-focus);
      background: white;
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    .tickets-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .ticket-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      background: var(--bg-light);
      border-radius: var(--radius);
      border: 1px solid var(--border-color);
    }

    .ticket-info h4 {
      color: var(--text-dark);
      margin-bottom: 0.25rem;
      font-size: 1rem;
    }

    .ticket-info p {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .ticket-status {
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .ticket-status.open {
      background: #fef3c7;
      color: #92400e;
    }

    .ticket-status.resolved {
      background: #d1fae5;
      color: #065f46;
    }

    .ticket-status.pending {
      background: #dbeafe;
      color: #1e40af;
    }

    .faq-preview {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .faq-item {
      padding: 1.5rem;
      background: var(--bg-light);
      border-radius: var(--radius);
      border-left: 4px solid var(--btn-bg);
    }

    .faq-item h3 {
      color: var(--text-dark);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
    }

    .faq-item p {
      color: var(--text-light);
      line-height: 1.5;
    }

    .faq-actions {
      text-align: center;
    }

    /* Live Chat Widget */
    .live-chat-widget {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
    }

    .chat-toggle {
      width: 60px;
      height: 60px;
      background: var(--btn-bg);
      color: white;
      border: none;
      border-radius: 50%;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .chat-toggle:hover {
      background: var(--btn-hover);
      transform: scale(1.1);
    }

    .chat-window {
      position: absolute;
      bottom: 70px;
      right: 0;
      width: 350px;
      background: white;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border-color);
      display: none;
      flex-direction: column;
      max-height: 500px;
    }

    .chat-window.open {
      display: flex;
    }

    .chat-header {
      background: var(--btn-bg);
      color: white;
      padding: 1rem;
      border-radius: var(--radius) var(--radius) 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .chat-header h4 {
      margin: 0;
      font-size: 1rem;
    }

    .close-chat {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
    }

    .chat-messages {
      flex: 1;
      padding: 1rem;
      overflow-y: auto;
      max-height: 300px;
    }

    .message {
      margin-bottom: 1rem;
      padding: 0.75rem;
      border-radius: var(--radius);
      max-width: 80%;
    }

    .message.agent {
      background: var(--bg-light);
      margin-right: auto;
    }

    .chat-input {
      display: flex;
      padding: 1rem;
      border-top: 1px solid var(--border-color);
    }

    .chat-input input {
      flex: 1;
      padding: 0.75rem;
      border: 1px solid var(--input-border);
      border-radius: var(--radius) 0 0 var(--radius);
      border-right: none;
    }

    .chat-input button {
      background: var(--btn-bg);
      color: white;
      border: none;
      padding: 0.75rem 1rem;
      border-radius: 0 var(--radius) var(--radius) 0;
      cursor: pointer;
    }

    /* Message Styles */
    .message.success {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
      padding: 1rem;
      border-radius: var(--radius);
    }

    .message.error {
      background: #fecaca;
      color: #dc2626;
      border: 1px solid #fca5a5;
      padding: 1rem;
      border-radius: var(--radius);
    }

    /* Include all other CSS styles from dashboard.php */
    /* ... */
  </style>
</body>
</html>