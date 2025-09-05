<?php
$pageTitle = 'Community - FoodFusion';
include 'includes/header.php';

// Get community stats
$totalUsers = 0;
$totalRecipes = 0;
$totalTips = 0;
$recentUsers = [];

try {
    global $db;
    
    // Get total users
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users");
    $stmt->execute();
    $result = $stmt->fetch();
    $totalUsers = $result['count'];
    
    // Get total recipes
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM recipes");
    $stmt->execute();
    $result = $stmt->fetch();
    $totalRecipes = $result['count'];
    
    // Get total cooking tips
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM cooking_tips");
    $stmt->execute();
    $result = $stmt->fetch();
    $totalTips = $result['count'];
    
    // Get recent users
    $stmt = $db->prepare("SELECT firstName, lastName, profile_image, created_at FROM users ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $recentUsers = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error silently
}
?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-100 to-teal-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Our 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-green-600">
                    Community
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Connect with passionate home cooks, share your culinary creations, and learn from fellow food enthusiasts
            </p>
        </div>
    </section>

    <!-- Community Stats -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Community by the Numbers</h2>
                <p class="text-lg text-gray-600">Our vibrant community in action</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2"><?php echo number_format($totalUsers); ?></div>
                    <div class="text-gray-600 font-medium">Active Members</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2"><?php echo number_format($totalRecipes); ?></div>
                    <div class="text-gray-600 font-medium">Recipes Shared</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2"><?php echo number_format($totalTips); ?></div>
                    <div class="text-gray-600 font-medium">Cooking Tips</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">50+</div>
                    <div class="text-gray-600 font-medium">Countries</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Members -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Recent Community Members</h2>
                <p class="text-lg text-gray-600">Welcome our newest food enthusiasts</p>
            </div>
            
            <?php if (!empty($recentUsers)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($recentUsers as $user): ?>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <img src="<?php echo $user['profile_image'] ? 'uploads/' . $user['profile_image'] : 'https://via.placeholder.com/80x80/78C841/FFFFFF?text=' . substr($user['firstName'], 0, 1); ?>" 
                         alt="Profile" class="w-16 h-16 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?>
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">
                        Joined <?php echo formatDate($user['created_at']); ?>
                    </p>
                    <div class="flex justify-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            New Member
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No members yet</h3>
                <p class="text-gray-600">Be the first to join our community!</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Community Guidelines -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Community Guidelines</h2>
                <p class="text-lg text-gray-600">Help us maintain a welcoming and supportive environment</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-heart text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Be Respectful</h3>
                    <p class="text-gray-600">
                        Treat all community members with kindness and respect. We're all here to learn and share our love for food.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-share-alt text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Share Authentically</h3>
                    <p class="text-gray-600">
                        Share your own recipes, experiences, and tips. Give credit where it's due and be honest about your sources.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-lightbulb text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Help Others Learn</h3>
                    <p class="text-gray-600">
                        Share constructive feedback and helpful tips. We're all at different skill levels, and that's what makes us stronger.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-flag text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Report Issues</h3>
                    <p class="text-gray-600">
                        If you see something that violates our guidelines, please report it. Help us keep the community safe and welcoming.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-comments text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Engage Positively</h3>
                    <p class="text-gray-600">
                        Ask questions, share your experiences, and engage in meaningful discussions about food and cooking.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-star text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Celebrate Success</h3>
                    <p class="text-gray-600">
                        Celebrate the achievements of fellow community members. A little encouragement goes a long way!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Community CTA -->
    <section class="py-16 bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Join Our Community?</h2>
            <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                Connect with fellow food enthusiasts, share your recipes, and learn from our amazing community of cooks.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php?page=register" class="bg-white hover:bg-gray-100 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Join Now
                </a>
                <a href="index.php?page=recipes" class="bg-green-700 hover:bg-green-800 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-white">
                    <i class="fas fa-book-open mr-2"></i>
                    Explore Recipes
                </a>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
