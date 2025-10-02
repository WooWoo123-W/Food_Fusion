<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$stmt = $pdo->query("SELECT title, description, category, file_url FROM culinary_resources ORDER BY category, title");
$resources = $stmt->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title">Culinary Resources</h1>
    <p class="card">Downloadable guides, kitchen cheat sheets, and technique videos curated by FoodFusion chefs. Filter by category to find what you need instantly.</p>
    <div class="resources-nav">
        <button data-resource-filter="all">All</button>
        <button data-resource-filter="guides">Guides</button>
        <button data-resource-filter="videos">Videos</button>
        <button data-resource-filter="cheatsheets">Cheat Sheets</button>
    </div>
    <div class="resource-grid">
        <?php foreach ($resources as $resource): ?>
            <article class="card" data-category="<?php echo sanitize($resource['category']); ?>">
                <h3><?php echo sanitize($resource['title']); ?></h3>
                <p><?php echo sanitize($resource['description']); ?></p>
                <a class="btn" href="<?php echo sanitize($resource['file_url']); ?>" download>Download</a>
            </article>
        <?php endforeach; ?>
        <?php if (empty($resources)): ?>
            <p>No resources uploaded yet.</p>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
