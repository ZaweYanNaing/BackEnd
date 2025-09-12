-- FoodFusion Database Schema for BEfood

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    location VARCHAR(100),
    website VARCHAR(255),
    profile_image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_name (firstName, lastName)
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create cuisine_types table
CREATE TABLE IF NOT EXISTS cuisine_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create recipes table
CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructions TEXT NOT NULL,
    cooking_time INT,
    difficulty ENUM('Easy', 'Medium', 'Hard') DEFAULT 'Medium',
    user_id INT NOT NULL,
    image_url VARCHAR(500),
    servings INT,
    cuisine_type_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (cuisine_type_id) REFERENCES cuisine_types(id) ON DELETE SET NULL
);

-- Create recipe_categories table for many-to-many relationship
CREATE TABLE IF NOT EXISTS recipe_categories (
    recipe_id INT,
    category_id INT,
    PRIMARY KEY (recipe_id, category_id),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Create ingredients table
CREATE TABLE IF NOT EXISTS ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create recipe_ingredients junction table for many-to-many relationship
CREATE TABLE IF NOT EXISTS recipe_ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    ingredient_id INT NOT NULL,
    quantity VARCHAR(50),
    unit VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE,
    UNIQUE KEY unique_recipe_ingredient (recipe_id, ingredient_id),
    INDEX idx_recipe_ingredients (recipe_id),
    INDEX idx_ingredient_recipes (ingredient_id)
);

-- Create user_favorites table for favorite recipes
CREATE TABLE IF NOT EXISTS user_favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipe_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_recipe (user_id, recipe_id),
    INDEX idx_user_favorites (user_id, recipe_id)
);

-- Create user_activity table for tracking user actions
CREATE TABLE IF NOT EXISTS user_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type ENUM('recipe_created', 'recipe_updated', 'recipe_deleted', 'recipe_liked', 'recipe_unliked', 'recipe_favorited', 'recipe_unfavorited', 'recipe_rated', 'recipe_reviewed', 'recipe_shared', 'resource_downloaded', 'profile_updated') NOT NULL,
    target_id INT,
    target_type ENUM('recipe', 'resource', 'profile') NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_activity (user_id, activity_type, created_at)
);

-- Create recipe_likes table for recipe likes
CREATE TABLE IF NOT EXISTS recipe_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipe_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_recipe_like (user_id, recipe_id),
    INDEX idx_recipe_likes (recipe_id, user_id)
);

-- Create recipe_reviews table for user reviews with ratings
CREATE TABLE IF NOT EXISTS recipe_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipe_id INT NOT NULL,
    review_text TEXT NOT NULL,
    rating ENUM('1', '2', '3', '4', '5') NOT NULL DEFAULT '5',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_recipe_review (user_id, recipe_id),
    INDEX idx_recipe_reviews (recipe_id, user_id),
    INDEX idx_user_reviews (user_id),
    INDEX idx_recipe_rating (recipe_id, rating)
);

-- Create recipe_views table for tracking recipe views
CREATE TABLE IF NOT EXISTS recipe_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    recipe_id INT NOT NULL,
    ip_address VARCHAR(45),
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    INDEX idx_recipe_views (recipe_id, viewed_at),
    INDEX idx_user_views (user_id, viewed_at),
    INDEX idx_ip_views (ip_address, viewed_at)
);

-- Create cooking_tips table
CREATE TABLE IF NOT EXISTS cooking_tips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id INT NOT NULL,
    prep_time INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_tips (user_id),
    INDEX idx_tip_created (created_at)
);

-- Create tip_likes table for cooking tip likes
CREATE TABLE IF NOT EXISTS tip_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tip_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tip_id) REFERENCES cooking_tips(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_tip_like (user_id, tip_id),
    INDEX idx_tip_likes (tip_id, user_id)
);

-- Insert sample data
INSERT INTO categories (name, description) VALUES
('Breakfast', 'Morning meals and brunch recipes'),
('Lunch', 'Midday meal recipes'),
('Dinner', 'Evening meal recipes'),
('Dessert', 'Sweet treats and desserts'),
('Snacks', 'Quick bites and appetizers'),
('Vegetarian', 'Plant-based recipes'),
('Vegan', 'Plant-based recipes without animal products'),
('Gluten-Free', 'Recipes without gluten'),
('Quick & Easy', 'Fast and simple recipes'),
('Healthy', 'Nutritious and balanced meals');

-- Insert sample cuisine types
INSERT INTO cuisine_types (name, description) VALUES
('American', 'Traditional American cuisine'),
('Italian', 'Italian Mediterranean cuisine'),
('Asian', 'Various Asian cuisines'),
('Mexican', 'Mexican and Latin American cuisine'),
('Mediterranean', 'Mediterranean and Middle Eastern cuisine'),
('Indian', 'Indian subcontinent cuisine'),
('French', 'French haute cuisine'),
('Japanese', 'Japanese cuisine'),
('Thai', 'Thai cuisine'),
('Greek', 'Greek Mediterranean cuisine');

-- Insert sample ingredients
INSERT INTO ingredients (name) VALUES
('All-purpose flour'),
('Eggs'),
('Milk'),
('Butter'),
('Sugar'),
('Salt'),
('Chicken breast'),
('Soy sauce'),
('Garlic'),
('Ginger'),
('Vegetable oil'),
('Mixed vegetables'),
('Onions'),
('Tomatoes'),
('Rice'),
('Pasta'),
('Cheese'),
('Olive oil'),
('Black pepper'),
('Basil');

-- Insert sample user (password: password123)
INSERT INTO users (firstName, lastName, email, password, bio, location) VALUES
('Admin', 'User', 'admin@foodfusion.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Passionate home cook who loves experimenting with new recipes and techniques.', 'New York, NY');

-- Insert sample recipes
INSERT INTO recipes (title, description, instructions, cooking_time, difficulty, user_id, servings, cuisine_type_id) VALUES
('Classic Pancakes', 'Fluffy and delicious breakfast pancakes', '1. Mix dry ingredients in a large bowl\n2. Beat eggs and milk in a separate bowl\n3. Combine wet and dry ingredients until just mixed\n4. Cook on griddle over medium heat until golden brown\n5. Flip and cook the other side', 20, 'Easy', 1, 4, 1),
('Chicken Stir Fry', 'Quick and healthy chicken stir fry with vegetables', '1. Cut chicken into bite-sized pieces\n2. Heat oil in a large wok or pan\n3. Stir fry chicken until golden and cooked through\n4. Add vegetables and stir fry for 2-3 minutes\n5. Add sauce and cook for another minute\n6. Serve over rice', 25, 'Medium', 1, 2, 3),
('Chocolate Chip Cookies', 'Soft and chewy chocolate chip cookies', '1. Preheat oven to 375°F\n2. Cream butter and sugars together\n3. Beat in eggs and vanilla\n4. Mix in flour, baking soda, and salt\n5. Stir in chocolate chips\n6. Drop rounded tablespoons onto ungreased cookie sheet\n7. Bake for 9-11 minutes', 30, 'Easy', 1, 24, 1);

-- Insert sample recipe ingredients
INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit) VALUES
(1, 1, '2', 'cups'),      -- All-purpose flour
(1, 2, '2', 'pieces'),    -- Eggs
(1, 3, '1 3/4', 'cups'), -- Milk
(1, 4, '1/4', 'cup'),     -- Butter
(1, 5, '2', 'tablespoons'), -- Sugar
(1, 6, '1/2', 'teaspoon'),  -- Salt
(2, 7, '1', 'lb'),        -- Chicken breast
(2, 12, '2', 'cups'),     -- Mixed vegetables
(2, 8, '3', 'tablespoons'), -- Soy sauce
(2, 9, '2', 'cloves'),    -- Garlic
(2, 10, '1', 'tablespoon'), -- Ginger
(2, 11, '2', 'tablespoons'); -- Vegetable oil

-- Insert sample recipe categories
INSERT INTO recipe_categories (recipe_id, category_id) VALUES
(1, 1), -- Pancakes - Breakfast
(1, 9), -- Pancakes - Quick & Easy
(2, 2), -- Chicken Stir Fry - Lunch
(2, 6), -- Chicken Stir Fry - Healthy
(3, 4), -- Cookies - Dessert
(3, 9); -- Cookies - Quick & Easy

-- Create contact_messages table for contact form submissions
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_contact_status (status),
    INDEX idx_contact_email (email),
    INDEX idx_contact_created (created_at)
);

-- Insert sample cooking tips
INSERT INTO cooking_tips (title, content, user_id, prep_time) VALUES
('Perfect Rice Every Time', 'Use a 1:2 ratio of rice to water. Bring to a boil, then reduce heat to low and cover. Cook for 18 minutes without lifting the lid.', 1, 5),
('Knife Safety Tips', 'Always keep your knives sharp. A dull knife is more dangerous than a sharp one. Use a proper cutting board and keep your fingers curled under when chopping.', 1, 0),
('Seasoning Secrets', 'Taste your food as you cook and season in layers. Start with salt and pepper, then add herbs and spices. Remember, you can always add more, but you can\'t take it away.', 1, 0);

-- Create educational_resources table
CREATE TABLE IF NOT EXISTS educational_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type ENUM('document','infographic','video','presentation','guide') NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    download_count INT NOT NULL DEFAULT 0,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_resource_type (type),
    INDEX idx_resource_created (created_at)
);
