<?php
$pageTitle = 'Profile - FoodFusion';
include 'includes/header.php';

// Require login
requireLogin();

$error = '';
$success = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = sanitizeInput($_POST['firstName'] ?? '');
    $lastName = sanitizeInput($_POST['lastName'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $bio = sanitizeInput($_POST['bio'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');
    $website = sanitizeInput($_POST['website'] ?? '');
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $error = 'First name, last name, and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if email is already taken by another user
        $existingUser = getUserByEmail($email);
        if ($existingUser && $existingUser['id'] != $user['id']) {
            $error = 'This email is already taken by another user.';
        } else {
            // Update user
            $userData = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'bio' => $bio,
                'location' => $location,
                'website' => $website,
                'profile_image' => $user['profile_image'] // Keep existing image
            ];
            
            if (updateUser($user['id'], $userData)) {
                // Update session user data
                $_SESSION['user_id'] = $user['id'];
                $user = getUserById($user['id']); // Refresh user data
                $_SESSION['toast_message'] = 'Profile updated successfully!';
                $_SESSION['toast_type'] = 'success';
            } else {
                $error = 'Failed to update profile. Please try again.';
            }
        }
    }
}

// Get user's recipes
$userRecipes = getAllRecipes(['user_id' => $user['id']]);

// Get user's cooking tips
$userTips = [];
try {
    global $db;
    $stmt = $db->prepare("SELECT * FROM cooking_tips WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user['id']]);
    $userTips = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error silently
}
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                <div class="flex-shrink-0">
                    <?php if ($user['profile_image']): ?>
                        <img src="uploads/<?php echo $user['profile_image']; ?>" 
                             alt="Profile" class="w-24 h-24 rounded-full object-cover">
                    <?php else: ?>
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900">
                        <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?>
                    </h1>
                    <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($user['email']); ?></p>
                    <?php if ($user['location']): ?>
                    <p class="text-gray-500 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        <?php echo htmlspecialchars($user['location']); ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($user['bio']): ?>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="flex space-x-4">
                    <a href="index.php?page=edit-profile" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2"><?php echo count($userRecipes); ?></div>
                <div class="text-gray-600">Recipes</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2"><?php echo count($userTips); ?></div>
                <div class="text-gray-600">Cooking Tips</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    <?php
                    try {
                        global $db;
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM recipe_likes rl JOIN recipes r ON rl.recipe_id = r.id WHERE r.user_id = ?");
                        $stmt->execute([$user['id']]);
                        $result = $stmt->fetch();
                        echo $result['count'];
                    } catch (Exception $e) {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="text-gray-600">Recipe Likes</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    <?php
                    try {
                        global $db;
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM user_favorites WHERE user_id = ?");
                        $stmt->execute([$user['id']]);
                        $result = $stmt->fetch();
                        echo $result['count'];
                    } catch (Exception $e) {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="text-gray-600">Favorites</div>
            </div>
        </div>
        
        <!-- Content Tabs -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="showTab('recipes')" id="recipes-tab" 
                            class="py-4 px-1 border-b-2 border-green-500 text-green-600 font-medium text-sm">
                        My Recipes
                    </button>
                    <button onclick="showTab('tips')" id="tips-tab" 
                            class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                        My Cooking Tips
                    </button>
                </nav>
            </div>
            
            <div class="p-6">
                <!-- Recipes Tab -->
                <div id="recipes-content" class="tab-content">
                    <?php if (!empty($userRecipes)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($userRecipes as $recipe): ?>
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="index.php?page=recipe-detail&id=<?php echo $recipe['id']; ?>" 
                                   class="hover:text-green-600 transition-colors">
                                    <?php echo htmlspecialchars($recipe['title']); ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                <?php echo htmlspecialchars(substr($recipe['description'], 0, 100)) . (strlen($recipe['description']) > 100 ? '...' : ''); ?>
                            </p>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span><?php echo formatDate($recipe['created_at']); ?></span>
                                <div class="flex items-center space-x-2">
                                    <span><i class="fas fa-heart mr-1"></i><?php echo $recipe['total_likes']; ?></span>
                                    <span><i class="fas fa-star mr-1"></i><?php echo number_format($recipe['average_rating'], 1); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No recipes yet</h3>
                        <p class="text-gray-600 mb-4">Start sharing your culinary creations!</p>
                        <a href="index.php?page=create-recipe" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                            Create Your First Recipe
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Tips Tab -->
                <div id="tips-content" class="tab-content hidden">
                    <?php if (!empty($userTips)): ?>
                    <div class="space-y-4">
                        <?php foreach ($userTips as $tip): ?>
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($tip['title']); ?>
                            </h3>
                            <p class="text-gray-600 mb-3">
                                <?php echo nl2br(htmlspecialchars($tip['content'])); ?>
                            </p>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span><?php echo formatDate($tip['created_at']); ?></span>
                                <?php if ($tip['prep_time']): ?>
                                <span><i class="fas fa-stopwatch mr-1"></i><?php echo $tip['prep_time']; ?> min prep</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-lightbulb text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No cooking tips yet</h3>
                        <p class="text-gray-600 mb-4">Share your cooking wisdom with the community!</p>
                        <a href="index.php?page=cooking-tips" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                            Share a Cooking Tip
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('[id$="-tab"]').forEach(tab => {
        tab.classList.remove('border-green-500', 'text-green-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-green-500', 'text-green-600');
}
</script>

<?php include 'includes/footer.php'; ?>
