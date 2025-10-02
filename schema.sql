CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    failed_attempts INT NOT NULL DEFAULT 0,
    locked_until DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    type VARCHAR(60) NOT NULL,
    difficulty VARCHAR(40) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE community_recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE recipe_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES community_recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE culinary_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    file_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE educational_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    file_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    role VARCHAR(120) NOT NULL,
    bio TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    display_order INT DEFAULT 0
);

INSERT INTO users (name, email, password_hash, role) VALUES
('Site Admin', 'admin@foodfusion.com', '$2y$12$V5b9BXYChFiTGjOIUwsL/ugDp7FzLNqtYqISB9IqUzFNlxbWoP3Ii', 'admin');

INSERT INTO recipes (title, type, difficulty, description, ingredients, instructions, is_featured) VALUES
('Rosewater Pistachio Cupcakes', 'dessert', 'medium', 'Delicate cupcakes infused with rosewater and topped with pistachio buttercream.', 'Flour, sugar, butter, eggs, rosewater, pistachios', 'Whip butter and sugar, fold in dry ingredients, bake at 350°F for 18 minutes.', 1),
('Spiced Mango Quinoa Bowl', 'entree', 'easy', 'A vibrant bowl mixing mango, quinoa, and spiced chickpeas.', 'Quinoa, mango, chickpeas, spices', 'Cook quinoa, roast chickpeas with spices, assemble with mango and herbs.', 1),
('Lavender Lemonade Fizz', 'beverage', 'easy', 'Sparkling lemonade elevated with lavender syrup.', 'Lemons, sugar, sparkling water, lavender', 'Create lavender syrup, mix with fresh lemon juice and sparkling water.', 0);

INSERT INTO team_members (name, role, bio, display_order) VALUES
('Luna Mae', 'Founder & Creative Chef', 'Conceptualizes dreamy flavor mashups and leads the culinary direction.', 1),
('Isla Ray', 'Community Curator', 'Fosters community contributions and curates the cookbook features.', 2),
('Mira Sol', 'Education Lead', 'Designs culinary lessons and resource kits for educators.', 3);
