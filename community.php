<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$message = '';
$alertType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_recipe']) && is_logged_in()) {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ingredients = trim($_POST['ingredients'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        if ($title && $description && $ingredients && $instructions) {
            $stmt = $pdo->prepare("INSERT INTO community_recipes (user_id, title, description, ingredients, instructions) VALUES (:user_id, :title, :description, :ingredients, :instructions)");
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':title' => $title,
                ':description' => $description,
                ':ingredients' => $ingredients,
                ':instructions' => $instructions,
            ]);
            $message = 'Recipe submitted! Our admins will review it shortly.';
            $alertType = 'success';
        } else {
            $message = 'Please complete all recipe fields before submitting.';
            $alertType = 'error';
        }
    }
    if (isset($_POST['submit_comment']) && is_logged_in()) {
        $recipeId = (int)($_POST['recipe_id'] ?? 0);
        $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
        $comment = trim($_POST['comment'] ?? '');
        if ($recipeId && $comment) {
            $stmt = $pdo->prepare("INSERT INTO recipe_comments (recipe_id, user_id, rating, comment) VALUES (:recipe_id, :user_id, :rating, :comment)");
            $stmt->execute([
                ':recipe_id' => $recipeId,
                ':user_id' => $_SESSION['user_id'],
                ':rating' => $rating,
                ':comment' => $comment,
            ]);
            $message = 'Comment added! Thanks for sharing your thoughts.';
            $alertType = 'success';
        } else {
            $message = 'Please add a comment to share your experience.';
            $alertType = 'error';
        }
    }
}

$communityStmt = $pdo->query("SELECT id, title, description FROM community_recipes WHERE status = 'approved' ORDER BY created_at DESC");
$communityRecipes = $communityStmt->fetchAll();

$commentStmt = $pdo->prepare("SELECT rc.recipe_id, rc.rating, rc.comment, u.name FROM recipe_comments rc JOIN users u ON rc.user_id = u.id WHERE rc.recipe_id = :recipe_id ORDER BY rc.created_at DESC");
$commentsByRecipe = [];
foreach ($communityRecipes as $communityRecipe) {
    $commentStmt->execute([':recipe_id' => $communityRecipe['id']]);
    $commentsByRecipe[$communityRecipe['id']] = $commentStmt->fetchAll();
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="community-hero">
    <div>
        <h1>Community Cookbook</h1>
        <p>Where FoodFusion members pour their hearts into every shared recipe. Browse approved creations, sprinkle in your ratings, and add your personal twist.</p>
        <button class="btn" data-join-modal>Join the Cookbook</button>
    </div>
    <div class="card">
        <h3>How it works</h3>
        <ul>
            <li>Submit your recipe for moderation.</li>
            <li>Gather ratings and comments from fellow foodies.</li>
            <li>Earn spotlight features on the homepage.</li>
        </ul>
    </div>
</section>
<?php if ($message): ?>
    <div class="alert <?php echo $alertType; ?>"><?php echo sanitize($message); ?></div>
<?php endif; ?>
<section>
    <h2 class="section-title">Approved Community Recipes</h2>
    <div class="community-grid">
        <?php foreach ($communityRecipes as $communityRecipe): ?>
            <article class="card">
                <h3><?php echo sanitize($communityRecipe['title']); ?></h3>
                <p><?php echo sanitize($communityRecipe['description']); ?></p>
                <details>
                    <summary>Comments & Ratings</summary>
                    <div class="comment-list">
                        <?php foreach ($commentsByRecipe[$communityRecipe['id']] as $comment): ?>
                            <div class="comment">
                                <strong><?php echo sanitize($comment['name']); ?></strong>
                                <p>Rating: <?php echo (int)$comment['rating']; ?>/5</p>
                                <p><?php echo sanitize($comment['comment']); ?></p>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($commentsByRecipe[$communityRecipe['id']])): ?>
                            <p>No comments yet. Be the first!</p>
                        <?php endif; ?>
                    </div>
                </details>
                <?php if (is_logged_in()): ?>
                    <form method="post" class="comment-form">
                        <input type="hidden" name="recipe_id" value="<?php echo (int)$communityRecipe['id']; ?>">
                        <div class="form-group">
                            <label for="rating-<?php echo (int)$communityRecipe['id']; ?>">Rating</label>
                            <select name="rating" id="rating-<?php echo (int)$communityRecipe['id']; ?>">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment-<?php echo (int)$communityRecipe['id']; ?>">Comment</label>
                            <textarea name="comment" id="comment-<?php echo (int)$communityRecipe['id']; ?>" rows="3"></textarea>
                        </div>
                        <button class="btn" type="submit" name="submit_comment">Submit Comment</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.php">Login</a> to add your ratings and comments.</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if (empty($communityRecipes)): ?>
            <p>Approved community recipes will appear here after admin review.</p>
        <?php endif; ?>
    </div>
</section>
<section>
    <h2 class="section-title">Submit Your Recipe</h2>
    <?php if (is_logged_in()): ?>
        <form method="post" class="card">
            <div class="form-group">
                <label for="title">Recipe Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Short Description</label>
                <textarea name="description" id="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="ingredients">Ingredients</label>
                <textarea name="ingredients" id="ingredients" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="instructions">Instructions</label>
                <textarea name="instructions" id="instructions" rows="4" required></textarea>
            </div>
            <button class="btn" type="submit" name="submit_recipe">Submit for Review</button>
        </form>
    <?php else: ?>
        <p class="card">Please <a href="login.php">login</a> or <a href="register.php">register</a> to submit your own recipe.</p>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
