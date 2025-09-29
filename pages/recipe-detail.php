<?php
$pageTitle = 'Recipe Detail - FoodFusion';
include 'includes/header.php';

$recipeId = $_GET['id'] ?? 0;
$recipe = getRecipeById($recipeId);

if (!$recipe) {
    include 'pages/404.php';
    exit;
}



// Get recipe reviews
$reviews = getRecipeReviews($recipeId);

// Check if user has liked/favorited this recipe
$userLiked = false;
$userFavorited = false;
if ($isLoggedIn) {
    try {
        global $db;
        
        // Check if user liked this recipe
        $stmt = $db->prepare("SELECT id FROM recipe_likes WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user['id'], $recipeId]);
        $userLiked = $stmt->fetch() ? true : false;
        
        // Check if user favorited this recipe
        $stmt = $db->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$user['id'], $recipeId]);
        $userFavorited = $stmt->fetch() ? true : false;
    } catch (Exception $e) {
        // Handle error silently
    }
}

// Handle like/favorite actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $isLoggedIn) {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'like') {
        $result = toggleRecipeLike($user['id'], $recipeId);
        if ($result['liked']) {
            $_SESSION['toast_message'] = 'Recipe liked!';
            $_SESSION['toast_type'] = 'success';
        } else {
            $_SESSION['toast_message'] = 'Recipe unliked!';
            $_SESSION['toast_type'] = 'info';
        }
        echo '<script>location.assign("index.php?page=recipe-detail&id=' . $recipeId . '");</script>';
        exit;
    } elseif ($action == 'favorite') {
        $result = toggleFavorite($user['id'], $recipeId);
        if ($result['favorited']) {
            $_SESSION['toast_message'] = 'Added to favorites!';
            $_SESSION['toast_type'] = 'success';
        } else {
            $_SESSION['toast_message'] = 'Removed from favorites!';
            $_SESSION['toast_type'] = 'info';
        }
        echo '<script>location.assign("index.php?page=recipe-detail&id=' . $recipeId . '");</script>';
        exit;
    } elseif ($action == 'review') {
        $rating = $_POST['rating'] ?? 0;
        $reviewText = sanitizeInput($_POST['review_text'] ?? '');
        
        if ($rating > 0 && !empty($reviewText)) {
            addRecipeReview($user['id'], $recipeId, $rating, $reviewText);
            $_SESSION['toast_message'] = 'Review submitted!';
            $_SESSION['toast_type'] = 'success';
            echo '<script>location.assign("index.php?page=recipe-detail&id=' . $recipeId . '");</script>';
            exit;
        }
    }
}

$pageTitle = $recipe['title'] . ' - FoodFusion';
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Recipe Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <?php if ($recipe['image_url']): ?>
            <!-- Image -->
            <img src="uploads/<?php echo htmlspecialchars($recipe['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($recipe['title']); ?>" 
                 class="w-full h-64 md:h-96 object-cover">
            <?php else: ?>
            <!-- No Media -->
            <div class="w-full h-64 md:h-96 bg-gray-200 flex items-center justify-center">
                <i class="fas fa-image text-6xl text-gray-400"></i>
            </div>
            <?php endif; ?>
            
            <div class="p-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                                <?php echo htmlspecialchars($recipe['title']); ?>
                            </h1>
                            
                            <!-- Edit/Delete buttons for recipe owner -->
                            <?php if ($isLoggedIn && $recipe['user_id'] == $user['id']): ?>
                            <div class="flex space-x-2 mr-3">
                                <a href="index.php?page=edit-recipe&id=<?php echo $recipeId; ?>" 
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                                <a href="index.php?page=delete-recipe&id=<?php echo $recipeId; ?>" 
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors"
                                   onclick="return confirm('Are you sure you want to delete this recipe? This action cannot be undone.')">
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Social Share -->
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Share this recipe:</h3>
                            <?php 
                            $shareUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                            $shareTitle = htmlspecialchars($recipe['title']);
                            $shareDescription = htmlspecialchars($recipe['description']);
                            ?>
                            <div class="social-share-buttons flex flex-wrap gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($shareUrl); ?>" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fab fa-facebook-f mr-2"></i>
                                    Facebook
                                </a>
                                
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($shareUrl); ?>&text=<?php echo urlencode($shareTitle); ?>" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-400 rounded-md hover:bg-blue-500 transition-colors">
                                    <i class="fab fa-twitter mr-2"></i>
                                    Twitter
                                </a>
                                
                                <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode($shareUrl); ?>&description=<?php echo urlencode($shareDescription); ?>" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                    <i class="fab fa-pinterest-p mr-2"></i>
                                    Pinterest
                                </a>
                                
                                <a href="https://wa.me/?text=<?php echo urlencode($shareTitle . ' ' . $shareUrl); ?>" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    WhatsApp
                                </a>
                                
                                <button onclick="copyToClipboard('<?php echo $shareUrl; ?>')" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-link mr-2"></i>
                                    Copy Link
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-6 text-gray-600 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                <?php echo htmlspecialchars($recipe['firstName'] . ' ' . $recipe['lastName']); ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                <?php echo $recipe['cooking_time'] ? $recipe['cooking_time'] . ' minutes' : 'N/A'; ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                <?php echo $recipe['servings'] ? $recipe['servings'] . ' servings' : 'N/A'; ?>
                            </span>
                            <?php if ($recipe['video_url']): ?>
                            <span class="flex items-center text-green-600">
                                <i class="fas fa-video mr-2"></i>
                                Video Available
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <?php echo htmlspecialchars($recipe['difficulty']); ?>
                            </span>
                            <?php if ($recipe['cuisine_type']): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?php echo htmlspecialchars($recipe['cuisine_type']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($isLoggedIn): ?>
                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="like">
                            <button type="submit" class="flex items-center px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors cursor-pointer">
                                <i class="fas fa-heart mr-2 <?php echo $userLiked ? 'text-red-500' : 'text-gray-400'; ?>"></i>
                                <span class="text-sm font-medium"><?php echo $userLiked ? 'Liked' : 'Like'; ?></span>
                            </button>
                        </form>
                        
                        <form method="POST" class="inline">
                            <input type="hidden" name="action" value="favorite">
                            <button type="submit" class="flex items-center px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors cursor-pointer">
                                <i class="fas fa-bookmark mr-2 <?php echo $userFavorited ? 'text-yellow-500' : 'text-gray-400'; ?>"></i>
                                <span class="text-sm font-medium"><?php echo $userFavorited ? 'Favorited' : 'Favorite'; ?></span>
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($recipe['description']): ?>
                <p class="text-lg text-gray-700 mb-6">
                    <?php echo nl2br(htmlspecialchars($recipe['description'])); ?>
                </p>
                <?php endif; ?>
                
                <!-- Categories -->
                <?php if (!empty($recipe['categories'])): ?>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php foreach ($recipe['categories'] as $category): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        <?php echo htmlspecialchars($category); ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ingredients -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-list-ul mr-2"></i>
                        Ingredients
                    </h2>
                    
                    <?php if (!empty($recipe['ingredients'])): ?>
                    <ul class="space-y-3">
                        <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                        <li class="flex items-center">
                            <i class="fas fa-circle text-green-500 text-xs mr-3"></i>
                            <span class="text-gray-700">
                                <?php if ($ingredient['quantity']): ?>
                                <span class="font-medium"><?php echo htmlspecialchars($ingredient['quantity']); ?></span>
                                <?php if ($ingredient['unit']): ?>
                                <span class="text-gray-500"><?php echo htmlspecialchars($ingredient['unit']); ?></span>
                                <?php endif; ?>
                                <span class="text-gray-500">of</span>
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($ingredient['name']); ?></span>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-gray-500">No ingredients listed.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Instructions and Reviews -->
            <div class="lg:col-span-2">
                <!-- Instructions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        Instructions
                    </h2>
                    <div class="prose max-w-none">
                        <?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?>
                    </div>
                </div>
                
        <!-- Video Section -->
        <?php if ($recipe['video_url']): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-video mr-2"></i>
                Cooking Video
            </h2>
            <div class="relative w-full bg-black rounded-lg overflow-hidden">
                <video controls class="w-full h-96 object-contain">
                    <source src="uploads/<?php echo htmlspecialchars($recipe['video_url']); ?>" type="video/mp4" >
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        <?php endif; ?>
                
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-star mr-2"></i>
                Reviews
            </h2>
            
            <?php if ($isLoggedIn): ?>
            <!-- Add Review Form -->
            <form method="POST" class="mb-6 p-4 bg-gray-50 rounded-lg">
                <input type="hidden" name="action" value="review">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                    <div class="flex items-center space-x-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" class="sr-only">
                        <label for="star<?php echo $i; ?>" class="text-2xl cursor-pointer text-gray-300 hover:text-yellow-400">
                            ★
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="review_text" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                    <textarea name="review_text" id="review_text" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Share your thoughts about this recipe..."></textarea>
                </div>
                
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                    Submit Review
                </button>
            </form>
            <?php endif; ?>
            
            <!-- Reviews List -->
            <?php if (!empty($reviews)): ?>
            <div class="space-y-4">
                <?php foreach ($reviews as $review): ?>
                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <img src="<?php echo $review['profile_image'] ? 'uploads/' . $review['profile_image'] : 'https://via.placeholder.com/32x32/78C841/FFFFFF?text=' . substr($review['firstName'], 0, 1); ?>" 
                                 alt="Profile" class="w-8 h-8 rounded-full mr-3">
                            <div>
                                <h4 class="font-medium text-gray-900">
                                    <?php echo htmlspecialchars($review['firstName'] . ' ' . $review['lastName']); ?>
                                </h4>
                                <div class="flex items-center">
                                    <?php echo generateStars($review['rating']); ?>
                                    <span class="ml-2 text-sm text-gray-500">
                                        <?php echo formatDate($review['created_at']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-500 text-center py-8">No reviews yet. Be the first to review this recipe!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Star rating functionality
document.querySelectorAll('input[name="rating"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const rating = parseInt(this.value);
        const labels = document.querySelectorAll('label[for^="star"]');
        
        labels.forEach((label, index) => {
            if (index < rating) {
                label.classList.remove('text-gray-300');
                label.classList.add('text-yellow-400');
            } else {
                label.classList.remove('text-yellow-400');
                label.classList.add('text-gray-300');
            }
        });
    });
});

// Hover effect for stars
document.querySelectorAll('label[for^="star"]').forEach((label, index) => {
    label.addEventListener('mouseenter', function() {
        const rating = index + 1;
        const labels = document.querySelectorAll('label[for^="star"]');
        
        labels.forEach((l, i) => {
            if (i < rating) {
                l.classList.remove('text-gray-300');
                l.classList.add('text-yellow-400');
            }
        });
    });
    
    label.addEventListener('mouseleave', function() {
        const checkedRadio = document.querySelector('input[name="rating"]:checked');
        const rating = checkedRadio ? parseInt(checkedRadio.value) : 0;
        const labels = document.querySelectorAll('label[for^="star"]');
        
        labels.forEach((l, i) => {
            if (i < rating) {
                l.classList.remove('text-gray-300');
                l.classList.add('text-yellow-400');
            } else {
                l.classList.remove('text-yellow-400');
                l.classList.add('text-gray-300');
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
