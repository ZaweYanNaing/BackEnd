<?php
$pageTitle = 'Search - FoodFusion';
include 'includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

$query = $_GET['q'] ?? '';
$category = $_GET['category'] ?? '';
$difficulty = $_GET['difficulty'] ?? '';
$max_cooking_time = $_GET['max_cooking_time'] ?? '';

$recipes = [];
$tips = [];

if (!empty($query)) {
    // Search recipes
    $filters = [];
    if ($category) $filters['category'] = $category;
    if ($difficulty) $filters['difficulty'] = $difficulty;
    if ($max_cooking_time) $filters['max_cooking_time'] = $max_cooking_time;
    
    $recipes = searchRecipes($query, $filters);
    
    // Search cooking tips
    try {
        global $db;
        $stmt = $db->prepare("SELECT ct.*, u.firstName, u.lastName, u.profile_image 
                              FROM cooking_tips ct 
                              JOIN users u ON ct.user_id = u.id 
                              WHERE ct.title LIKE ? OR ct.content LIKE ?
                              ORDER BY ct.created_at DESC");
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm]);
        $tips = $stmt->fetchAll();
    } catch (Exception $e) {
        // Handle error silently
    }
}

$categories = getCategories();
?>

<div class="min-h-screen bg-gray-50">
    <!-- Search Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Search</h1>
            
            <!-- Search Form -->
            <form method="GET" class="space-y-4">
                <input type="hidden" name="page" value="search">
                
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" 
                               placeholder="Search recipes and cooking tips..."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg">
                    </div>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>
                        Search
                    </button>
                </div>
                
                <!-- Advanced Filters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Dietary Preference</label>
                        <select name="category" id="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">All Preferences</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                        <select name="difficulty" id="difficulty" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">All Levels</option>
                            <option value="Easy" <?php echo $difficulty == 'Easy' ? 'selected' : ''; ?>>Easy</option>
                            <option value="Medium" <?php echo $difficulty == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="Hard" <?php echo $difficulty == 'Hard' ? 'selected' : ''; ?>>Hard</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="max_cooking_time" class="block text-sm font-medium text-gray-700 mb-1">Max Cooking Time</label>
                        <select name="max_cooking_time" id="max_cooking_time" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Any Time</option>
                            <option value="30" <?php echo $max_cooking_time == '30' ? 'selected' : ''; ?>>30 minutes or less</option>
                            <option value="60" <?php echo $max_cooking_time == '60' ? 'selected' : ''; ?>>1 hour or less</option>
                            <option value="120" <?php echo $max_cooking_time == '120' ? 'selected' : ''; ?>>2 hours or less</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <?php if (!empty($query)): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Search Results for "<?php echo htmlspecialchars($query); ?>"
            </h2>
            <p class="text-gray-600">
                Found <?php echo count($recipes); ?> recipes and <?php echo count($tips); ?> cooking tips
            </p>
        </div>
        
        <!-- Recipes Results -->
        <?php if (!empty($recipes)): ?>
        <div class="mb-12">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-book-open mr-2"></i>
                Recipes (<?php echo count($recipes); ?>)
            </h3>
            
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
                        
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">
                            <a href="index.php?page=recipe-detail&id=<?php echo $recipe['id']; ?>" 
                               class="hover:text-green-600 transition-colors">
                                <?php echo htmlspecialchars($recipe['title']); ?>
                            </a>
                        </h4>
                        
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            <?php echo htmlspecialchars(substr($recipe['description'], 0, 100)) . (strlen($recipe['description']) > 100 ? '...' : ''); ?>
                        </p>
                        
                        <div class="flex items-center justify-between">
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
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Cooking Tips Results -->
        <?php if (!empty($tips)): ?>
        <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-lightbulb mr-2"></i>
                Cooking Tips (<?php echo count($tips); ?>)
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($tips as $tip): ?>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-lightbulb text-green-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                <?php echo htmlspecialchars($tip['title']); ?>
                            </h4>
                            <div class="flex items-center text-sm text-gray-500">
                                <img src="<?php echo $tip['profile_image'] ? 'uploads/' . $tip['profile_image'] : 'https://via.placeholder.com/24x24/78C841/FFFFFF?text=' . substr($tip['firstName'], 0, 1); ?>" 
                                     alt="Profile" class="w-6 h-6 rounded-full mr-2">
                                <span>by <?php echo htmlspecialchars($tip['firstName'] . ' ' . $tip['lastName']); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-gray-700 mb-4">
                        <?php echo nl2br(htmlspecialchars(substr($tip['content'], 0, 150)) . (strlen($tip['content']) > 150 ? '...' : '')); ?>
                    </p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>
                            <i class="fas fa-calendar mr-1"></i>
                            <?php echo formatDate($tip['created_at']); ?>
                        </span>
                        <?php if ($tip['prep_time']): ?>
                        <span>
                            <i class="fas fa-stopwatch mr-1"></i>
                            <?php echo $tip['prep_time']; ?> min prep
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- No Results -->
        <?php if (empty($recipes) && empty($tips)): ?>
        <div class="text-center py-12">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No results found</h3>
            <p class="text-gray-600">Try adjusting your search terms or filters.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <!-- Search Suggestions -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center py-12">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Search for recipes and cooking tips</h3>
            <p class="text-gray-600 mb-8">Enter a search term above to find what you're looking for.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <i class="fas fa-book-open text-3xl text-green-600 mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Search Recipes</h4>
                    <p class="text-gray-600 text-sm">Find recipes by name, ingredients, or cuisine type</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <i class="fas fa-lightbulb text-3xl text-green-600 mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Cooking Tips</h4>
                    <p class="text-gray-600 text-sm">Discover cooking techniques and tips from our community</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <i class="fas fa-filter text-3xl text-green-600 mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Advanced Filters</h4>
                    <p class="text-gray-600 text-sm">Use filters to narrow down your search results</p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
