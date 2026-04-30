<?php
/**
 * contact.php — Verdana Studio Contact Page
 * Handles form display and PHP server-side processing.
 */

$success  = false;
$errors   = [];
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $formData['name']    = trim(htmlspecialchars($_POST['name']    ?? ''));
    $formData['email']   = trim(htmlspecialchars($_POST['email']   ?? ''));
    $formData['phone']   = trim(htmlspecialchars($_POST['phone']   ?? ''));
    $formData['service'] = trim(htmlspecialchars($_POST['service'] ?? ''));
    $formData['budget']  = trim(htmlspecialchars($_POST['budget']  ?? ''));
    $formData['message'] = trim(htmlspecialchars($_POST['message'] ?? ''));

    // Honeypot spam check
    if (!empty($_POST['website'])) {
        die(); // Bot detected — silent exit
    }

    // Validate name
    if (empty($formData['name']))
        $errors['name'] = 'Your name is required.';
    elseif (strlen($formData['name']) < 2)
        $errors['name'] = 'Name must be at least 2 characters.';

    // Validate email
    if (empty($formData['email']))
        $errors['email'] = 'Your email address is required.';
    elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Please enter a valid email address.';

    // Validate phone (optional)
    if (!empty($formData['phone']) && !preg_match('/^[0-9\+\-\s\(\)]{7,15}$/', $formData['phone']))
        $errors['phone'] = 'Please enter a valid phone number.';

    // Validate service
    if (empty($formData['service']))
        $errors['service'] = 'Please select a service.';

    // Validate message
    if (empty($formData['message']))
        $errors['message'] = 'Please write a message.';
    elseif (strlen($formData['message']) < 20)
        $errors['message'] = 'Message must be at least 20 characters.';

    // If no errors — send email
    if (empty($errors)) {
        $to      = 'hello@verdanastudio.in'; // ← Change to your real email
        $subject = 'New Project Enquiry — Verdana Studio';
        $body    = "Name: {$formData['name']}\n"
                 . "Email: {$formData['email']}\n"
                 . "Phone: " . ($formData['phone'] ?: 'N/A') . "\n"
                 . "Service: {$formData['service']}\n"
                 . "Budget: " . ($formData['budget'] ?: 'N/A') . "\n\n"
                 . "Message:\n{$formData['message']}";
        $headers = "From: noreply@verdanastudio.in\r\nReply-To: {$formData['email']}";

        // mail($to, $subject, $body, $headers); // Uncomment on live server
        $success  = true;
        $formData = [];
    }
}

// Helper: safe field value
function val(string $k, array $d): string {
    return htmlspecialchars($d[$k] ?? '');
}
// Helper: error span
function err(string $k, array $e): string {
    return isset($e[$k]) ? '<span class="field-error">' . $e[$k] . '</span>' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us — Verdana Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/contact.css" />
</head>
<body>

  <!-- NAVIGATION -->
  <nav id="navbar">
    <div class="nav-inner">
      <a href="index.html" class="logo">Verdana<span>.</span></a>
      <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
      <ul class="nav-links" id="nav-links">
        <li><a href="index.html#services">Services</a></li>
        <li><a href="index.html#work">Work</a></li>
        <li><a href="index.html#about">About</a></li>
        <li><a href="index.html#testimonials">Clients</a></li>
        <li><a href="contact.php" class="active">Contact</a></li>
      </ul>
      <a href="contact.php" class="nav-cta">Start a Project</a>
    </div>
  </nav>

  <!-- CONTACT HERO -->
  <section class="contact-hero">
    <div class="container">
      <span class="section-tag">Get In Touch</span>
      <h1>Let's build something<br /><em>great together.</em></h1>
      <p class="hero-sub">Have a project in mind? Fill in the form and we'll get back within 24 hours.</p>
    </div>
  </section>

  <!-- CONTACT BODY -->
  <section class="contact-body">
    <div class="container contact-grid">

      <!-- Info Panel -->
      <div class="contact-info">
        <h3>Studio Details</h3>
        <div class="info-item">
          <span class="info-icon">✉</span>
          <div><strong>Email</strong><a href="mailto:hello@verdanastudio.in">hello@verdanastudio.in</a></div>
        </div>
        <div class="info-item">
          <span class="info-icon">☎</span>
          <div><strong>Phone</strong><a href="tel:+919876543210">+91 98765 43210</a></div>
        </div>
        <div class="info-item">
          <span class="info-icon">◎</span>
          <div><strong>Address</strong><span>42 Design Lane, Connaught Place<br />New Delhi — 110001, India</span></div>
        </div>
        <div class="info-item">
          <span class="info-icon">◷</span>
          <div><strong>Hours</strong><span>Monday – Friday<br />9:00 AM – 6:00 PM IST</span></div>
        </div>
        <div class="info-socials">
          <a href="#" class="social-link">in</a>
          <a href="#" class="social-link">tw</a>
          <a href="#" class="social-link">dr</a>
          <a href="#" class="social-link">be</a>
        </div>
      </div>

      <!-- Form Panel -->
      <div class="contact-form-wrap">
        <?php if ($success): ?>
          <div class="form-success-box">
            <div class="success-icon">✓</div>
            <h3>Message Received!</h3>
            <p>Thank you for reaching out. We'll get back to you within 24 hours.</p>
            <a href="contact.php" class="btn-primary">Send Another Message</a>
          </div>
        <?php else: ?>

          <?php if (!empty($errors['general'])): ?>
            <div class="alert-error"><?= $errors['general'] ?></div>
          <?php endif; ?>

          <form action="contact.php" method="POST" id="contactForm" novalidate>

            <div class="form-row">
              <div class="form-group <?= isset($errors['name'])  ? 'has-error' : '' ?>">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" placeholder="Arjun Sharma" value="<?= val('name', $formData) ?>" required />
                <?= err('name', $errors) ?>
              </div>
              <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="arjun@company.com" value="<?= val('email', $formData) ?>" required />
                <?= err('email', $errors) ?>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                <label for="phone">Phone <span class="optional">(optional)</span></label>
                <input type="tel" id="phone" name="phone" placeholder="+91 98765 43210" value="<?= val('phone', $formData) ?>" />
                <?= err('phone', $errors) ?>
              </div>
              <div class="form-group <?= isset($errors['service']) ? 'has-error' : '' ?>">
                <label for="service">Service Required <span class="required">*</span></label>
                <select id="service" name="service" required>
                  <option value="" disabled <?= empty($formData['service']) ? 'selected' : '' ?>>Select a service…</option>
                  <?php foreach (['Brand Identity','Web Design & Development','UI / UX Design','Motion & Animation','Digital Strategy','Content & Copy','Full Project'] as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>" <?= val('service',$formData)===$s ? 'selected':'' ?>><?= htmlspecialchars($s) ?></option>
                  <?php endforeach; ?>
                </select>
                <?= err('service', $errors) ?>
              </div>
            </div>

            <div class="form-group">
              <label for="budget">Project Budget <span class="optional">(optional)</span></label>
              <select id="budget" name="budget">
                <option value="" disabled <?= empty($formData['budget']) ? 'selected':'' ?>>Select a range…</option>
                <?php foreach (['Under ₹50,000','₹50,000 – ₹1,00,000','₹1,00,000 – ₹3,00,000','₹3,00,000 – ₹5,00,000','Above ₹5,00,000'] as $b): ?>
                  <option value="<?= htmlspecialchars($b) ?>" <?= val('budget',$formData)===$b ? 'selected':'' ?>><?= htmlspecialchars($b) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group <?= isset($errors['message']) ? 'has-error' : '' ?>">
              <label for="message">Your Message <span class="required">*</span></label>
              <textarea id="message" name="message" rows="5" placeholder="Tell us about your project, goals, and timeline…" required><?= val('message', $formData) ?></textarea>
              <?= err('message', $errors) ?>
            </div>

            <!-- Honeypot (hidden from real users) -->
            <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none;" aria-hidden="true">
              <input type="text" name="website" tabindex="-1" autocomplete="off" />
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
              Send Message <span class="btn-arrow">→</span>
            </button>

          </form>
        <?php endif; ?>
      </div>

    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-grid container">
      <div class="footer-brand">
        <div class="logo">Verdana<span>.</span></div>
        <p>An independent creative studio designing digital experiences for brands that dare to stand out.</p>
      </div>
      <div class="footer-col"><h4>Services</h4><ul><li><a href="index.html#services">Brand Identity</a></li><li><a href="index.html#services">Web Design</a></li><li><a href="index.html#services">UI / UX</a></li></ul></div>
      <div class="footer-col"><h4>Studio</h4><ul><li><a href="index.html#about">About Us</a></li><li><a href="index.html#work">Our Work</a></li><li><a href="contact.php">Contact</a></li></ul></div>
      <div class="footer-col"><h4>Legal</h4><ul><li><a href="#">Privacy Policy</a></li><li><a href="#">Terms of Use</a></li></ul></div>
    </div>
    <div class="footer-bottom container">
      <p>© 2025 Verdana Studio Pvt. Ltd. All rights reserved.</p>
      <div class="social-links">
        <a class="social-link" href="#">in</a><a class="social-link" href="#">tw</a><a class="social-link" href="#">dr</a><a class="social-link" href="#">be</a>
      </div>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script src="js/contact.js"></script>
</body>
</html>
