<?php
$pageTitle = 'Recipes - FoodFusion';
include 'includes/header.php';

// Get filters from URL
$filters = [];
$filters['category'] = $_GET['category'] ?? '';
$filters['difficulty'] = $_GET['difficulty'] ?? '';
$filters['max_cooking_time'] = $_GET['max_cooking_time'] ?? '';
$filters['user_id'] = $_GET['user_id'] ?? '';

// Get recipes with filters
$recipes = getAllRecipes($filters);

// Get categories for filter dropdown
$categories = getCategories();
$cuisineTypes = getCuisineTypes();
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Recipes</h1>
                    <p class="mt-2 text-gray-600">Discover amazing recipes from our community</p>
                </div>
                
                <?php if ($isLoggedIn): ?>
                <div class="mt-4 md:mt-0">
                    <a href="index.php?page=create-recipe" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Create Recipe
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="hidden" name="page" value="recipes">
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $filters['category'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                    <select name="difficulty" id="difficulty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Levels</option>
                        <option value="Easy" <?php echo $filters['difficulty'] == 'Easy' ? 'selected' : ''; ?>>Easy</option>
                        <option value="Medium" <?php echo $filters['difficulty'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="Hard" <?php echo $filters['difficulty'] == 'Hard' ? 'selected' : ''; ?>>Hard</option>
                    </select>
                </div>
                
                <div>
                    <label for="max_cooking_time" class="block text-sm font-medium text-gray-700 mb-1">Max Cooking Time</label>
                    <select name="max_cooking_time" id="max_cooking_time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Any Time</option>
                        <option value="30" <?php echo $filters['max_cooking_time'] == '30' ? 'selected' : ''; ?>>30 minutes or less</option>
                        <option value="60" <?php echo $filters['max_cooking_time'] == '60' ? 'selected' : ''; ?>>1 hour or less</option>
                        <option value="120" <?php echo $filters['max_cooking_time'] == '120' ? 'selected' : ''; ?>>2 hours or less</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recipes Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (empty($recipes)): ?>
        <div class="text-center py-12">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No recipes found</h3>
            <p class="text-gray-600">Try adjusting your filters or create a new recipe.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($recipes as $recipe): ?>
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
                    
                    <!-- Categories -->
                    <?php if (!empty($recipe['categories'])): ?>
                    <div class="flex flex-wrap gap-1 mb-4">
                        <?php foreach (array_slice($recipe['categories'], 0, 3) as $category): ?>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            <?php echo htmlspecialchars($category); ?>
                        </span>
                        <?php endforeach; ?>
                        <?php if (count($recipe['categories']) > 3): ?>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            +<?php echo count($recipe['categories']) - 3; ?> more
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
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
