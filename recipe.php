<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT title, description, ingredients, instructions FROM recipes WHERE id = :id');
$stmt->execute([':id' => $id]);
$recipe = $stmt->fetch();
if (!$recipe) {
    redirect('recipes.php');
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title"><?php echo sanitize($recipe['title']); ?></h1>
    <article class="card">
        <h2>Overview</h2>
        <p><?php echo sanitize($recipe['description']); ?></p>
        <h3>Ingredients</h3>
        <p><?php echo nl2br(sanitize($recipe['ingredients'])); ?></p>
        <h3>Instructions</h3>
        <p><?php echo nl2br(sanitize($recipe['instructions'])); ?></p>
        <button class="btn" data-share data-url="recipe.php?id=<?php echo $id; ?>" data-title="<?php echo sanitize($recipe['title']); ?>">Share</button>
    </article>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
