<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$type = $_GET['type'] ?? 'all';
$difficulty = $_GET['difficulty'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$query = "SELECT id, title, type, difficulty, description FROM recipes WHERE 1=1";
$params = [];

if ($type !== 'all' && $type !== '') {
    $query .= " AND type = :type";
    $params[':type'] = $type;
}
if ($difficulty !== 'all' && $difficulty !== '') {
    $query .= " AND difficulty = :difficulty";
    $params[':difficulty'] = $difficulty;
}
if ($search !== '') {
    $query .= " AND (title LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}
$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$recipes = $stmt->fetchAll();

$typeStmt = $pdo->query("SELECT DISTINCT type FROM recipes ORDER BY type");
$types = $typeStmt->fetchAll(PDO::FETCH_COLUMN);
$difficultyStmt = $pdo->query("SELECT DISTINCT difficulty FROM recipes ORDER BY difficulty");
$levels = $difficultyStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title">Recipe Collection</h1>
    <form class="card" method="get" action="recipes.php">
        <div class="form-group">
            <label for="search">Search</label>
            <input type="search" name="search" id="search" placeholder="Search recipes" value="<?php echo sanitize($search); ?>">
        </div>
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type">
                <option value="all">All Types</option>
                <?php foreach ($types as $option): ?>
                    <option value="<?php echo sanitize($option); ?>" <?php if ($type === $option) echo 'selected'; ?>><?php echo sanitize(ucfirst($option)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="difficulty">Difficulty</label>
            <select name="difficulty" id="difficulty">
                <option value="all">All Levels</option>
                <?php foreach ($levels as $level): ?>
                    <option value="<?php echo sanitize($level); ?>" <?php if ($difficulty === $level) echo 'selected'; ?>><?php echo sanitize(ucfirst($level)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn" type="submit">Filter</button>
    </form>
    <div class="recipes-grid">
        <?php foreach ($recipes as $recipe): ?>
            <article class="card">
                <span class="badge"><?php echo sanitize(ucfirst($recipe['type'])); ?> · <?php echo sanitize(ucfirst($recipe['difficulty'])); ?></span>
                <h3><?php echo sanitize($recipe['title']); ?></h3>
                <p><?php echo sanitize($recipe['description']); ?></p>
                <div class="share-buttons">
                    <button class="btn" data-share data-url="recipe.php?id=<?php echo (int)$recipe['id']; ?>" data-title="<?php echo sanitize($recipe['title']); ?>">Share</button>
                </div>
            </article>
        <?php endforeach; ?>
        <?php if (empty($recipes)): ?>
            <p>No recipes found. Try adjusting your filters.</p>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
