<?php
require_once __DIR__ . '/../config/database.php';

// Suppress linter errors for $db variable type
// The $db variable is a PDO object from config/database.php

// User functions
function getUserById($id) {
    /** @var PDO $db */
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getUserByEmail($email) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function createUser($data) {
    global $db;
    $stmt = $db->prepare("INSERT INTO users (firstName, lastName, email, password, bio, location, website, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([
        $data['firstName'],
        $data['lastName'],
        $data['email'],
        password_hash($data['password'], PASSWORD_DEFAULT),
        $data['bio'] ?? '',
        $data['location'] ?? '',
        $data['website'] ?? ''
    ]);
}

function updateUser($id, $data) {
    global $db;
    $stmt = $db->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ?, bio = ?, location = ?, website = ?, profile_image = ?, updated_at = NOW() WHERE id = ?");
    return $stmt->execute([
        $data['firstName'],
        $data['lastName'],
        $data['email'],
        $data['bio'] ?? '',
        $data['location'] ?? '',
        $data['website'] ?? '',
        $data['profile_image'] ?? '',
        $id
    ]);
}

// Recipe functions
function getAllRecipes($filters = []) {
    global $db;
    
    $whereClause = "WHERE 1=1";
    $params = [];
    
    if (!empty($filters['category'])) {
        $whereClause .= " AND r.id IN (SELECT recipe_id FROM recipe_categories WHERE category_id = ?)";
        $params[] = $filters['category'];
    }
    
    if (!empty($filters['difficulty'])) {
        $whereClause .= " AND r.difficulty = ?";
        $params[] = $filters['difficulty'];
    }
    
    if (!empty($filters['max_cooking_time'])) {
        $whereClause .= " AND r.cooking_time <= ?";
        $params[] = $filters['max_cooking_time'];
    }
    
    if (!empty($filters['user_id'])) {
        $whereClause .= " AND r.user_id = ?";
        $params[] = $filters['user_id'];
    }
    
    $query = "SELECT r.*, u.firstName, u.lastName, 
              GROUP_CONCAT(DISTINCT c.name) as categories,
              ct.name as cuisine_type,
              COALESCE(rr.avg_rating, 0) as average_rating,
              COALESCE(rr.total_ratings, 0) as total_ratings,
              COALESCE(rl.total_likes, 0) as total_likes
              FROM recipes r 
              LEFT JOIN users u ON r.user_id = u.id
              LEFT JOIN recipe_categories rc ON r.id = rc.recipe_id
              LEFT JOIN categories c ON rc.category_id = c.id
              LEFT JOIN cuisine_types ct ON r.cuisine_type_id = ct.id
              LEFT JOIN (
                  SELECT recipe_id, 
                         AVG(CAST(rating AS DECIMAL(3,2))) as avg_rating, 
                         COUNT(*) as total_ratings
                  FROM recipe_reviews 
                  GROUP BY recipe_id
              ) rr ON r.id = rr.recipe_id
              LEFT JOIN (
                  SELECT recipe_id, COUNT(*) as total_likes
                  FROM recipe_likes 
                  GROUP BY recipe_id
              ) rl ON r.id = rl.recipe_id
              $whereClause
              GROUP BY r.id
              ORDER BY r.created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll();
    
    // Process categories
    foreach ($recipes as &$recipe) {
        if ($recipe['categories']) {
            $recipe['categories'] = explode(',', $recipe['categories']);
        } else {
            $recipe['categories'] = [];
        }
        $recipe['average_rating'] = (float) $recipe['average_rating'];
        $recipe['total_ratings'] = (int) $recipe['total_ratings'];
        $recipe['total_likes'] = (int) $recipe['total_likes'];
    }
    
    return $recipes;
}

function getRecipeById($id) {
    global $db;
    $stmt = $db->prepare("SELECT r.*, u.firstName, u.lastName,
                         GROUP_CONCAT(DISTINCT c.name) as categories,
                         ct.name as cuisine_type,
                         GROUP_CONCAT(DISTINCT CONCAT(i.name, ':', ri.quantity, ' ', ri.unit) SEPARATOR '|') as ingredients
                  FROM recipes r 
                  LEFT JOIN users u ON r.user_id = u.id
                  LEFT JOIN recipe_categories rc ON r.id = rc.recipe_id
                  LEFT JOIN categories c ON rc.category_id = c.id
                  LEFT JOIN cuisine_types ct ON r.cuisine_type_id = ct.id
                  LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
                  LEFT JOIN ingredients i ON ri.ingredient_id = i.id
                  WHERE r.id = ?");
    $stmt->execute([$id]);
    $recipe = $stmt->fetch();
    
    if ($recipe) {
        // Process categories
        if ($recipe['categories']) {
            $recipe['categories'] = explode(',', $recipe['categories']);
        } else {
            $recipe['categories'] = [];
        }
        
        // Process ingredients
        if ($recipe['ingredients']) {
            $ingredientsArray = [];
            $ingredientParts = explode('|', $recipe['ingredients']);
            foreach ($ingredientParts as $part) {
                if (strpos($part, ':') !== false) {
                    list($name, $quantity) = explode(':', $part, 2);
                    $ingredientsArray[] = [
                        'name' => $name,
                        'quantity' => $quantity
                    ];
                }
            }
            $recipe['ingredients'] = $ingredientsArray;
        } else {
            $recipe['ingredients'] = [];
        }
    }
    
    return $recipe;
}

function createRecipe($data) {
    global $db;
    
    try {
        $db->beginTransaction();
        
        // Insert recipe
        $stmt = $db->prepare("INSERT INTO recipes (title, description, instructions, cooking_time, difficulty, user_id, image_url, video_url, servings, cuisine_type_id, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['instructions'],
            $data['cooking_time'] ?? null,
            $data['difficulty'] ?? 'Medium',
            $data['user_id'],
            $data['image_url'] ?? null,
            $data['video_url'] ?? null,
            $data['servings'] ?? null,
            $data['cuisine_type_id'] ?? null
        ]);
        
        $recipeId = $db->lastInsertId();
        
        // Handle categories
        if (!empty($data['categories'])) {
            foreach ($data['categories'] as $categoryId) {
                $stmt = $db->prepare("INSERT INTO recipe_categories (recipe_id, category_id) VALUES (?, ?)");
                $stmt->execute([$recipeId, $categoryId]);
            }
        }
        
        // Handle ingredients
        if (!empty($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingredient) {
                // Get or create ingredient
                $ingredientId = getOrCreateIngredient($ingredient['name']);
                
                // Insert recipe-ingredient relationship
                $stmt = $db->prepare("INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit) VALUES (?, ?, ?, ?)");
                $stmt->execute([$recipeId, $ingredientId, $ingredient['quantity'], $ingredient['unit'] ?? '']);
            }
        }
        
        $db->commit();
        return $recipeId;
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

function updateRecipe($data) {
    global $db;
    
    try {
        $db->beginTransaction();
        
        // Update recipe
        $stmt = $db->prepare("UPDATE recipes SET title = ?, description = ?, instructions = ?, cooking_time = ?, difficulty = ?, image_url = ?, video_url = ?, servings = ?, cuisine_type_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['instructions'],
            $data['cooking_time'] ?? null,
            $data['difficulty'] ?? 'Medium',
            $data['image_url'] ?? null,
            $data['video_url'] ?? null,
            $data['servings'] ?? null,
            $data['cuisine_type_id'] ?? null,
            $data['id']
        ]);
        
        $recipeId = $data['id'];
        
        // Remove existing categories
        $stmt = $db->prepare("DELETE FROM recipe_categories WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        
        // Add new categories
        if (!empty($data['categories'])) {
            foreach ($data['categories'] as $categoryId) {
                $stmt = $db->prepare("INSERT INTO recipe_categories (recipe_id, category_id) VALUES (?, ?)");
                $stmt->execute([$recipeId, $categoryId]);
            }
        }
        
        // Remove existing ingredients
        $stmt = $db->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        
        // Add new ingredients
        if (!empty($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingredient) {
                // Get or create ingredient
                $ingredientId = getOrCreateIngredient($ingredient['name']);
                
                // Insert recipe-ingredient relationship
                $stmt = $db->prepare("INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit) VALUES (?, ?, ?, ?)");
                $stmt->execute([$recipeId, $ingredientId, $ingredient['quantity'], $ingredient['unit'] ?? '']);
            }
        }
        
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

function deleteRecipe($id, $userId) {
    global $db;
    
    // Check if user owns the recipe
    $stmt = $db->prepare("SELECT user_id FROM recipes WHERE id = ?");
    $stmt->execute([$id]);
    $recipe = $stmt->fetch();
    
    if (!$recipe || $recipe['user_id'] != $userId) {
        return false;
    }
    
    try {
        $db->beginTransaction();
        
        // Delete related records
        $stmt = $db->prepare("DELETE FROM recipe_categories WHERE recipe_id = ?");
        $stmt->execute([$id]);
        
        $stmt = $db->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
        $stmt->execute([$id]);
        
        $stmt = $db->prepare("DELETE FROM recipe_likes WHERE recipe_id = ?");
        $stmt->execute([$id]);
        
        $stmt = $db->prepare("DELETE FROM recipe_reviews WHERE recipe_id = ?");
        $stmt->execute([$id]);
        
        $stmt = $db->prepare("DELETE FROM user_favorites WHERE recipe_id = ?");
        $stmt->execute([$id]);
        
        $stmt = $db->prepare("DELETE FROM recipe_views WHERE recipe_id = ?");
        $stmt->execute([$id]);
        
        // Delete recipe
        $stmt = $db->prepare("DELETE FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        
        $db->commit();
        return true;
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

function searchRecipes($query, $filters = []) {
    global $db;
    
    $whereClause = "WHERE 1=1";
    $params = [];
    
    if (!empty($query)) {
        $whereClause .= " AND (r.title LIKE ? OR r.description LIKE ?)";
        $params[] = "%$query%";
        $params[] = "%$query%";
    }
    
    if (!empty($filters['category'])) {
        $whereClause .= " AND r.id IN (SELECT recipe_id FROM recipe_categories WHERE category_id = ?)";
        $params[] = $filters['category'];
    }
    
    if (!empty($filters['difficulty'])) {
        $whereClause .= " AND r.difficulty = ?";
        $params[] = $filters['difficulty'];
    }
    
    if (!empty($filters['max_cooking_time'])) {
        $whereClause .= " AND r.cooking_time <= ?";
        $params[] = $filters['max_cooking_time'];
    }
    
    $query = "SELECT r.*, u.firstName, u.lastName, 
              GROUP_CONCAT(DISTINCT c.name) as categories,
              ct.name as cuisine_type,
              COALESCE(rr.avg_rating, 0) as average_rating,
              COALESCE(rr.total_ratings, 0) as total_ratings,
              COALESCE(rl.total_likes, 0) as total_likes
              FROM recipes r 
              LEFT JOIN users u ON r.user_id = u.id
              LEFT JOIN recipe_categories rc ON r.id = rc.recipe_id
              LEFT JOIN categories c ON rc.category_id = c.id
              LEFT JOIN cuisine_types ct ON r.cuisine_type_id = ct.id
              LEFT JOIN (
                  SELECT recipe_id, 
                         AVG(CAST(rating AS DECIMAL(3,2))) as avg_rating, 
                         COUNT(*) as total_ratings
                  FROM recipe_reviews 
                  GROUP BY recipe_id
              ) rr ON r.id = rr.recipe_id
              LEFT JOIN (
                  SELECT recipe_id, COUNT(*) as total_likes
                  FROM recipe_likes 
                  GROUP BY recipe_id
              ) rl ON r.id = rl.recipe_id
              $whereClause
              GROUP BY r.id
              ORDER BY r.created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll();
    
    // Process categories
    foreach ($recipes as &$recipe) {
        if ($recipe['categories']) {
            $recipe['categories'] = explode(',', $recipe['categories']);
        } else {
            $recipe['categories'] = [];
        }
        $recipe['average_rating'] = (float) $recipe['average_rating'];
        $recipe['total_ratings'] = (int) $recipe['total_ratings'];
        $recipe['total_likes'] = (int) $recipe['total_likes'];
    }
    
    return $recipes;
}

// Helper functions
function getOrCreateIngredient($name) {
    global $db;
    
    // Check if ingredient exists
    $stmt = $db->prepare("SELECT id FROM ingredients WHERE name = ?");
    $stmt->execute([$name]);
    $ingredient = $stmt->fetch();
    
    if ($ingredient) {
        return $ingredient['id'];
    }
    
    // Create new ingredient
    $stmt = $db->prepare("INSERT INTO ingredients (name) VALUES (?)");
    $stmt->execute([$name]);
    return $db->lastInsertId();
}

function getCategories() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM categories ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCuisineTypes() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM cuisine_types ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getIngredients() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM ingredients ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Social features
function toggleRecipeLike($userId, $recipeId) {
    global $db;
    
    // Check if already liked
    $stmt = $db->prepare("SELECT id FROM recipe_likes WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$userId, $recipeId]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Remove like
        $stmt = $db->prepare("DELETE FROM recipe_likes WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$userId, $recipeId]);
        return ['liked' => false];
    } else {
        // Add like
        $stmt = $db->prepare("INSERT INTO recipe_likes (user_id, recipe_id) VALUES (?, ?)");
        $stmt->execute([$userId, $recipeId]);
        return ['liked' => true];
    }
}

function addRecipeReview($userId, $recipeId, $rating, $reviewText) {
    global $db;
    
    // Check if user already reviewed this recipe
    $stmt = $db->prepare("SELECT id FROM recipe_reviews WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$userId, $recipeId]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Update existing review
        $stmt = $db->prepare("UPDATE recipe_reviews SET rating = ?, review_text = ?, updated_at = NOW() WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$rating, $reviewText, $userId, $recipeId]);
    } else {
        // Create new review
        $stmt = $db->prepare("INSERT INTO recipe_reviews (user_id, recipe_id, rating, review_text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $recipeId, $rating, $reviewText]);
    }
    
    return true;
}

function getRecipeReviews($recipeId) {
    global $db;
    $stmt = $db->prepare("SELECT rr.*, u.firstName, u.lastName, u.profile_image 
                          FROM recipe_reviews rr 
                          JOIN users u ON rr.user_id = u.id 
                          WHERE rr.recipe_id = ? 
                          ORDER BY rr.created_at DESC");
    $stmt->execute([$recipeId]);
    return $stmt->fetchAll();
}

function toggleFavorite($userId, $recipeId) {
    global $db;
    
    // Check if already favorited
    $stmt = $db->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$userId, $recipeId]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Remove from favorites
        $stmt = $db->prepare("DELETE FROM user_favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$userId, $recipeId]);
        return ['favorited' => false];
    } else {
        // Add to favorites
        $stmt = $db->prepare("INSERT INTO user_favorites (user_id, recipe_id) VALUES (?, ?)");
        $stmt->execute([$userId, $recipeId]);
        return ['favorited' => true];
    }
}

function getUserFavorites($userId) {
    global $db;
    $stmt = $db->prepare("SELECT r.*, u.firstName, u.lastName 
                          FROM user_favorites uf 
                          JOIN recipes r ON uf.recipe_id = r.id 
                          JOIN users u ON r.user_id = u.id 
                          WHERE uf.user_id = ? 
                          ORDER BY uf.created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function trackRecipeView($recipeId, $userId = null) {
    global $db;
    
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Check for duplicate view (same user/IP within last 5 minutes)
    if ($userId) {
        $stmt = $db->prepare("SELECT id FROM recipe_views 
                              WHERE recipe_id = ? AND user_id = ? 
                              AND viewed_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
                              LIMIT 1");
        $stmt->execute([$recipeId, $userId]);
    } else {
        $stmt = $db->prepare("SELECT id FROM recipe_views 
                              WHERE recipe_id = ? AND user_id IS NULL AND ip_address = ? 
                              AND viewed_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
                              LIMIT 1");
        $stmt->execute([$recipeId, $ipAddress]);
    }
    
    if ($stmt->fetch()) {
        return false; // Duplicate view
    }
    
    // Insert new view
    $stmt = $db->prepare("INSERT INTO recipe_views (recipe_id, user_id, ip_address, viewed_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$recipeId, $userId, $ipAddress]);
    return true;
}

// Utility functions
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php');
    }
}

function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

function formatDateTime($date) {
    return date('M j, Y g:i A', strtotime($date));
}

function generateStars($rating) {
    $stars = '';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $fullStars) {
            $stars .= '<span class="text-yellow-400">★</span>';
        } elseif ($i == $fullStars + 1 && $halfStar) {
            $stars .= '<span class="text-yellow-400">☆</span>';
        } else {
            $stars .= '<span class="text-gray-300">★</span>';
        }
    }
    
    return $stars;
}
?>
