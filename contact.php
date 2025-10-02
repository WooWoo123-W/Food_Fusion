<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$name = '';
$email = '';
$message = '';
$status = '';
$statusType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message,
        ]);
        $status = 'Thank you! Your message is on its way to our kitchen team.';
        $statusType = 'success';
        $name = $email = $message = '';
    } else {
        $status = 'Please provide a valid name, email, and message.';
        $statusType = 'error';
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title">Contact FoodFusion</h1>
    <div class="contact-info card">
        <p>We love hearing from fellow foodies! Reach out for collaborations, press inquiries, or to share your culinary stories.</p>
        <p><strong>Email:</strong> hello@foodfusion.com</p>
        <p><strong>Studio:</strong> 88 Velvet Avenue, Suite 12, Flavor City</p>
        <p><strong>Hours:</strong> Weekdays 9am – 5pm (GMT)</p>
        <div class="social-share">
            <a href="https://www.instagram.com" target="_blank" rel="noopener">Instagram</a>
            <a href="https://www.youtube.com" target="_blank" rel="noopener">YouTube</a>
            <a href="https://www.tiktok.com" target="_blank" rel="noopener">TikTok</a>
        </div>
    </div>
    <?php if ($status): ?>
        <div class="alert <?php echo $statusType; ?>"><?php echo sanitize($status); ?></div>
    <?php endif; ?>
    <form method="post" class="card" novalidate>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required value="<?php echo sanitize($name); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?php echo sanitize($email); ?>">
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="4" required><?php echo sanitize($message); ?></textarea>
        </div>
        <button class="btn" type="submit">Send Message</button>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
