<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$email = '';
$message = '';
$type = 'error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT id, name, password_hash, role, failed_attempts, locked_until FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user) {
            if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                $message = 'Account locked due to multiple failed attempts. Try again later.';
            } elseif (password_verify($password, $user['password_hash'])) {
                $reset = $pdo->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = :id');
                $reset->execute([':id' => $user['id']]);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                redirect('index.php');
            } else {
                $attempts = (int)$user['failed_attempts'] + 1;
                $lockedUntil = null;
                if ($attempts >= 5) {
                    $lockedUntil = (new DateTime('+15 minutes'))->format('Y-m-d H:i:s');
                    $message = 'Too many failed attempts. Account locked for 15 minutes.';
                } else {
                    $message = 'Incorrect password. Attempts remaining: ' . (5 - $attempts);
                }
                $update = $pdo->prepare('UPDATE users SET failed_attempts = :attempts, locked_until = :locked_until WHERE id = :id');
                $update->execute([
                    ':attempts' => $attempts,
                    ':locked_until' => $lockedUntil,
                    ':id' => $user['id'],
                ]);
            }
        } else {
            $message = 'No account found with that email address.';
        }
    } else {
        $message = 'Please enter your email and password.';
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title">Welcome Back</h1>
    <p class="card">Log in to manage your community recipes, bookmark favorites, and download premium resources.</p>
    <?php if ($message): ?>
        <div class="alert error"><?php echo sanitize($message); ?></div>
    <?php endif; ?>
    <form method="post" class="card">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?php echo sanitize($email); ?>">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button class="btn" type="submit">Login</button>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
