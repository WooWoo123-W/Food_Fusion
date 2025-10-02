<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Fetch featured recipes
$stmt = $pdo->query("SELECT id, title, description, image_url FROM recipes WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3");
$featuredRecipes = $stmt->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section class="hero">
    <div class="hero-text">
        <span class="badge">Sweet & Savory Harmony</span>
        <h1>Celebrate the art of fusion cuisine with a sprinkle of sparkle.</h1>
        <p>FoodFusion is a girly, dreamy culinary sanctuary serving curated recipes, immersive cooking lessons, and a vibrant community cookbook created by foodies like you.</p>
        <div class="hero-actions">
            <button class="btn" data-join-modal>Join Our Kitchen Circle</button>
            <a class="btn" href="recipes.php">Explore Recipes</a>
        </div>
    </div>
    <div class="carousel" aria-label="Seasonal delights carousel">
        <div class="carousel-track">
            <article class="carousel-item">
                <h3>Seasonal Strawberry Mille-Feuille</h3>
                <p>Layered puff pastry with rose cream custard and macerated strawberries.</p>
            </article>
            <article class="carousel-item">
                <h3>Matcha Raspberry Tartlettes</h3>
                <p>Japanese matcha paired with zesty raspberries in buttery mini crusts.</p>
            </article>
            <article class="carousel-item">
                <h3>Golden Saffron Chai Latte</h3>
                <p>Aromatic chai infused with saffron threads, honey, and almond milk foam.</p>
            </article>
        </div>
        <div class="carousel-controls">
            <button data-carousel="prev" aria-label="Previous">‹</button>
            <button data-carousel="next" aria-label="Next">›</button>
        </div>
    </div>
</section>
<section>
    <h2 class="section-title">Featured Recipes</h2>
    <div class="featured-grid">
        <?php foreach ($featuredRecipes as $recipe): ?>
            <article class="card">
                <h3><?php echo sanitize($recipe['title']); ?></h3>
                <p><?php echo sanitize($recipe['description']); ?></p>
                <button class="btn" data-share data-url="recipe.php?id=<?php echo (int)$recipe['id']; ?>" data-title="<?php echo sanitize($recipe['title']); ?>">Share Recipe</button>
            </article>
        <?php endforeach; ?>
        <?php if (empty($featuredRecipes)): ?>
            <p>No featured recipes yet. Check back soon!</p>
        <?php endif; ?>
    </div>
</section>
<section>
    <h2 class="section-title">Why FoodFusion?</h2>
    <div class="featured-grid">
        <div class="card">
            <h3>Curated Culinary Playlists</h3>
            <p>Dynamic seasonal menus and themed cooking nights to keep your kitchen creative.</p>
        </div>
        <div class="card">
            <h3>Interactive Learning</h3>
            <p>Guided tutorials, live Q&A, and downloadable resources to master every technique.</p>
        </div>
        <div class="card">
            <h3>Community Love</h3>
            <p>Share your recipes, rate your favorites, and celebrate home chefs around the world.</p>
        </div>
    </div>
</section>
<section class="cta">
    <div class="card">
        <h2>Become part of our sparkling foodie sisterhood.</h2>
        <p>Unlock exclusive recipes, submit your culinary creations, and collect glittering badges.</p>
        <button class="btn" data-join-modal>Join Us</button>
    </div>
</section>
<div class="modal" id="joinModal" role="dialog" aria-modal="true" aria-labelledby="joinTitle">
    <div class="modal-content">
        <button class="nav-toggle" id="closeJoin" aria-label="Close">×</button>
        <h2 id="joinTitle">Join FoodFusion</h2>
        <p>Sign up to unlock recipe submissions, bookmarking, and more sparkle.</p>
        <form action="register.php" method="get">
            <button class="btn" type="submit">Register Now</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
