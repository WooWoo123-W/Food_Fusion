<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$teamStmt = $pdo->query("SELECT name, role, bio, image_url FROM team_members ORDER BY display_order");
$teamMembers = $teamStmt->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<section>
    <h1 class="section-title">About FoodFusion</h1>
    <div class="card">
        <p>FoodFusion was born from a dream to create a feminine, vibrant culinary studio where global flavors meet whimsical plating. We blend cultural traditions, modern techniques, and heartfelt storytelling to craft recipes that feel like love letters from our kitchen to yours.</p>
        <p>Our mission is to empower home chefs to explore fusion cuisine with confidence, creativity, and community. We champion mindful ingredients, sustainable sourcing, and inclusive kitchen spaces.</p>
    </div>
</section>
<section>
    <h2 class="section-title">Our Mission</h2>
    <div class="featured-grid">
        <div class="card">
            <h3>Celebrate Diversity</h3>
            <p>We highlight the magic that happens when cultures mingle, showcasing stories behind every dish.</p>
        </div>
        <div class="card">
            <h3>Empower Home Chefs</h3>
            <p>Accessible techniques, detailed guides, and supportive mentorship ignite culinary confidence.</p>
        </div>
        <div class="card">
            <h3>Foster Community</h3>
            <p>Our community cookbook, live events, and mentorship program nurture friendships around the table.</p>
        </div>
    </div>
</section>
<section>
    <h2 class="section-title">Meet the Team</h2>
    <div class="team-grid">
        <?php foreach ($teamMembers as $member): ?>
            <article class="card">
                <h3><?php echo sanitize($member['name']); ?></h3>
                <p class="badge"><?php echo sanitize($member['role']); ?></p>
                <p><?php echo sanitize($member['bio']); ?></p>
            </article>
        <?php endforeach; ?>
        <?php if (empty($teamMembers)): ?>
            <p>Our team profiles are coming soon.</p>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
