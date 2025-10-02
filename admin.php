<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_admin();

if (isset($_POST['update_status'])) {
    $recipeId = (int)($_POST['recipe_id'] ?? 0);
    $status = in_array($_POST['status'] ?? 'pending', ['pending', 'approved', 'rejected'], true) ? $_POST['status'] : 'pending';
    $stmt = $pdo->prepare('UPDATE community_recipes SET status = :status WHERE id = :id');
    $stmt->execute([':status' => $status, ':id' => $recipeId]);
}

if (isset($_POST['delete_comment'])) {
    $commentId = (int)($_POST['comment_id'] ?? 0);
    $pdo->prepare('DELETE FROM recipe_comments WHERE id = :id')->execute([':id' => $commentId]);
}

if (isset($_POST['delete_user'])) {
    $userId = (int)($_POST['user_id'] ?? 0);
    if ($userId !== (int)$_SESSION['user_id']) {
        $pdo->prepare('DELETE FROM users WHERE id = :id')->execute([':id' => $userId]);
    }
}

if (isset($_POST['upload_resource'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $url = trim($_POST['file_url'] ?? '');
    $table = $_POST['resource_type'] === 'educational' ? 'educational_resources' : 'culinary_resources';
    if ($title && $description && $category && $url) {
        $stmt = $pdo->prepare("INSERT INTO {$table} (title, description, category, file_url) VALUES (:title, :description, :category, :url)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category' => $category,
            ':url' => $url,
        ]);
    }
}

$pendingStmt = $pdo->query("SELECT id, title, description, status FROM community_recipes ORDER BY created_at DESC");
$pendingRecipes = $pendingStmt->fetchAll();
$comments = $pdo->query("SELECT rc.id, cr.title, rc.comment FROM recipe_comments rc JOIN community_recipes cr ON rc.recipe_id = cr.id ORDER BY rc.created_at DESC LIMIT 20")->fetchAll();
$users = $pdo->query("SELECT id, name, email, role FROM users ORDER BY created_at DESC")->fetchAll();
$contacts = $pdo->query("SELECT name, email, message, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 20")->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title">Admin Dashboard</h1>
    <div class="card">
        <h2>Moderate Community Recipes</h2>
        <?php foreach ($pendingRecipes as $pending): ?>
            <form method="post" class="moderation">
                <h3><?php echo sanitize($pending['title']); ?></h3>
                <p><?php echo sanitize($pending['description']); ?></p>
                <input type="hidden" name="recipe_id" value="<?php echo (int)$pending['id']; ?>">
                <select name="status">
                    <option value="pending" <?php if ($pending['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="approved" <?php if ($pending['status'] === 'approved') echo 'selected'; ?>>Approved</option>
                    <option value="rejected" <?php if ($pending['status'] === 'rejected') echo 'selected'; ?>>Rejected</option>
                </select>
                <button class="btn" type="submit" name="update_status">Update</button>
            </form>
        <?php endforeach; ?>
    </div>
    <div class="card">
        <h2>Recent Comments</h2>
        <?php foreach ($comments as $comment): ?>
            <form method="post" class="moderation">
                <p><strong><?php echo sanitize($comment['title']); ?>:</strong> <?php echo sanitize($comment['comment']); ?></p>
                <input type="hidden" name="comment_id" value="<?php echo (int)$comment['id']; ?>">
                <button class="btn" type="submit" name="delete_comment">Delete</button>
            </form>
        <?php endforeach; ?>
    </div>
    <div class="card">
        <h2>Manage Users</h2>
        <?php foreach ($users as $user): ?>
            <form method="post" class="moderation">
                <p><?php echo sanitize($user['name']); ?> (<?php echo sanitize($user['email']); ?>) — <?php echo sanitize($user['role']); ?></p>
                <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">
                <button class="btn" type="submit" name="delete_user">Delete</button>
            </form>
        <?php endforeach; ?>
    </div>
    <div class="card">
        <h2>Upload Resources</h2>
        <form method="post" class="resource-upload">
            <div class="form-group">
                <label for="resource_type">Resource Type</label>
                <select name="resource_type" id="resource_type">
                    <option value="culinary">Culinary</option>
                    <option value="educational">Educational</option>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" name="category" id="category" required>
            </div>
            <div class="form-group">
                <label for="file_url">File URL</label>
                <input type="url" name="file_url" id="file_url" required>
            </div>
            <button class="btn" type="submit" name="upload_resource">Upload</button>
        </form>
    </div>
    <div class="card">
        <h2>Contact Messages</h2>
        <ul>
            <?php foreach ($contacts as $contact): ?>
                <li><strong><?php echo sanitize($contact['name']); ?></strong> (<?php echo sanitize($contact['email']); ?>): <?php echo sanitize($contact['message']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
