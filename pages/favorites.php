<?php
$pageTitle = 'My Favorites - FoodFusion';
include 'includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

// Require login
requireLogin();

// Get user's favorite recipes
$favorites = getUserFavorites($user['id']);
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Favorites</h1>
                    <p class="mt-2 text-gray-600">Recipes you've saved for later</p>
                </div>
                
                <div class="text-sm text-gray-500">
                    <?php echo count($favorites); ?> favorite<?php echo count($favorites) !== 1 ? 's' : ''; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorites Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (empty($favorites)): ?>
        <div class="text-center py-12">
            <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No favorites yet</h3>
            <p class="text-gray-600 mb-8">Start exploring recipes and add them to your favorites!</p>
            <a href="index.php?page=recipes" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-search mr-2"></i>
                Browse Recipes
            </a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($favorites as $recipe): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <?php if ($recipe['image_url']): ?>
                <img src="uploads/<?php echo htmlspecialchars($recipe['image_url']); ?>" 
                     alt="<?php echo htmlspecialchars($recipe['title']); ?>" 
                     class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                <?php endif; ?>
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-user mr-1"></i>
                            <?php echo htmlspecialchars($recipe['firstName'] . ' ' . $recipe['lastName']); ?>
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            <?php echo $recipe['cooking_time'] ? $recipe['cooking_time'] . ' min' : 'N/A'; ?>
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <a href="index.php?page=recipe-detail&id=<?php echo $recipe['id']; ?>" 
                           class="hover:text-green-600 transition-colors">
                            <?php echo htmlspecialchars($recipe['title']); ?>
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-2">
                        <?php echo htmlspecialchars(substr($recipe['description'], 0, 100)) . (strlen($recipe['description']) > 100 ? '...' : ''); ?>
                    </p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <?php echo htmlspecialchars($recipe['difficulty']); ?>
                            </span>
                            <?php if ($recipe['cuisine_type']): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo htmlspecialchars($recipe['cuisine_type']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <span>
                                <i class="fas fa-heart mr-1"></i>
                                <?php echo $recipe['total_likes']; ?>
                            </span>
                            <span>
                                <i class="fas fa-star mr-1"></i>
                                <?php echo number_format($recipe['average_rating'], 1); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            <?php echo formatDate($recipe['created_at']); ?>
                        </span>
                        
                        <div class="flex items-center space-x-2">
                            <a href="index.php?page=recipe-detail&id=<?php echo $recipe['id']; ?>" 
                               class="text-green-600 hover:text-green-700 text-sm font-medium">
                                View Recipe
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
