# FoodFusion Platform

FoodFusion is a dreamy glassmorphism-inspired recipe platform that blends a vibrant front-end experience with a PHP/MySQL backend. It includes a comprehensive recipe catalogue, community cookbook, educational resources, and an admin dashboard for moderation.

## Features
- Responsive glassmorphism UI with transparent navigation and gold-accented branding.
- Dynamic homepage with carousel, featured recipes (from the database), join-us pop-up, and cookie consent banner.
- Recipe catalogue with advanced search and filtering by type and difficulty.
- Community cookbook with recipe submission (authenticated users), rating, and commenting workflows plus admin moderation.
- Culinary and educational resource libraries with category filters and downloads.
- Contact form with validation, privacy and cookie policy pages, and social share buttons.
- Authentication with registration, login, logout, password hashing, session management, and account lockout after 5 failed attempts.
- Admin dashboard to manage users, approve/reject community recipes, moderate comments, and upload resources.

## Tech Stack
- PHP 8+
- MySQL 5.7+
- HTML5, CSS (glassmorphism styling), Vanilla JavaScript

## Getting Started
1. Import the database schema and seed data:
   ```bash
   mysql -u <user> -p <database_name> < schema.sql
   ```
2. Configure database credentials using environment variables or edit `includes/db.php`:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`
3. Serve the project via PHP’s built-in server:
   ```bash
   php -S localhost:8000
   ```
4. Visit `http://localhost:8000` in your browser.

### Default Accounts
- **Admin:** `admin@foodfusion.com` (password: `admin123`)

## Database Schema Overview
The provided `schema.sql` defines tables for users, recipes, community recipes, comments, resources, contact messages, and team members.

## Security Notes
- Passwords are stored using PHP’s `password_hash`.
- Account lockout triggers for 15 minutes after 5 failed login attempts.
- All user-provided content is sanitized before rendering.

Enjoy building sweet fusion experiences! 🍰
