<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$name = '';
$email = '';
$message = '';
$type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $password !== $confirm) {
        $message = 'Please provide a name, valid email, and matching passwords of at least 8 characters.';
        $type = 'error';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $message = 'An account with this email already exists. Try logging in instead.';
            $type = 'error';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)');
            $insert->execute([
                ':name' => $name,
                ':email' => $email,
                ':password_hash' => $hash,
            ]);
            $message = 'Registration successful! Please log in.';
            $type = 'success';
            $name = $email = '';
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title">Join FoodFusion</h1>
    <p class="card">Create a free account to submit recipes, collect resources, and connect with our foodie community.</p>
    <?php if ($message): ?>
        <div class="alert <?php echo $type; ?>"><?php echo sanitize($message); ?></div>
    <?php endif; ?>
    <form method="post" class="card">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required value="<?php echo sanitize($name); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?php echo sanitize($email); ?>">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required minlength="8">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required minlength="8">
        </div>
        <button class="btn" type="submit">Register</button>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
