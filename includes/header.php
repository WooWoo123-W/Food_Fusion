<?php
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="cookie-banner" id="cookieBanner" role="dialog" aria-live="polite">
    <p>We use cookies to bring extra sweetness to your FoodFusion experience. Do you accept?</p>
    <div class="cookie-actions">
        <button class="btn" id="acceptCookies">Absolutely!</button>
        <a href="privacy.php">Privacy Policy</a>
        <a href="cookies.php">Cookie Policy</a>
    </div>
</div>
<header class="site-header">
    <div class="logo">Food<span>Fusion</span></div>
    <nav class="glass-nav" aria-label="Main navigation">
        <button class="nav-toggle" aria-controls="primary-menu" aria-expanded="false">☰</button>
        <ul id="primary-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="community.php">Community Cookbook</a></li>
            <li><a href="culinary_resources.php">Culinary Resources</a></li>
            <li><a href="educational_resources.php">Educational Resources</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (is_logged_in()): ?>
                <li><a href="logout.php">Logout</a></li>
                <?php if (is_admin()): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
