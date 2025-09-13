<?php
// Handle download requests FIRST, before any output
if (isset($_GET['download']) && isset($_GET['type']) && isset($_GET['id'])) {
    require_once 'config/database.php';
    require_once 'includes/functions.php';
    
    $downloadType = $_GET['type'];
    $resourceId = (int)$_GET['id'];
    
    try {
        global $db;
        
        if ($downloadType === 'recipe') {
            // Get recipe details
            $stmt = $db->prepare("SELECT r.*, u.firstName, u.lastName FROM recipes r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
            $stmt->execute([$resourceId]);
            $recipe = $stmt->fetch();
            
            if ($recipe) {
                // Get ingredients
                $stmt = $db->prepare("SELECT i.name, ri.quantity, ri.unit FROM recipe_ingredients ri JOIN ingredients i ON ri.ingredient_id = i.id WHERE ri.recipe_id = ?");
                $stmt->execute([$resourceId]);
                $ingredients = $stmt->fetchAll();
                
                // Generate downloadable recipe card content
                $content = "=== " . strtoupper($recipe['title']) . " ===\n\n";
                $content .= "Description: " . $recipe['description'] . "\n\n";
                $content .= "Cooking Time: " . $recipe['cooking_time'] . " minutes\n";
                $content .= "Difficulty: " . $recipe['difficulty'] . "\n";
                $content .= "Servings: " . $recipe['servings'] . "\n\n";
                
                $content .= "INGREDIENTS:\n";
                $content .= "============\n";
                foreach ($ingredients as $ingredient) {
                    $content .= "- " . $ingredient['quantity'] . " " . $ingredient['unit'] . " " . $ingredient['name'] . "\n";
                }
                
                $content .= "\nINSTRUCTIONS:\n";
                $content .= "=============\n";
                $content .= $recipe['instructions'] . "\n\n";
                
                $content .= "Recipe by: " . $recipe['firstName'] . " " . $recipe['lastName'] . "\n";
                $content .= "Downloaded from FoodFusion\n";
                
                $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $recipe['title']) . '_recipe.txt';
                
                header('Content-Type: text/plain');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Length: ' . strlen($content));
                echo $content;
                exit;
            }
        } elseif ($downloadType === 'educational_resource') {
            // Handle educational resource downloads
            $stmt = $db->prepare("SELECT * FROM educational_resources WHERE id = ?");
            $stmt->execute([$resourceId]);
            $resource = $stmt->fetch();
            
            if ($resource && file_exists($resource['file_path'])) {
                // Update download count
                $stmt = $db->prepare("UPDATE educational_resources SET download_count = download_count + 1 WHERE id = ?");
                $stmt->execute([$resourceId]);
                
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($resource['file_path']) . '"');
                readfile($resource['file_path']);
                exit;
            }
        }
    } catch (Exception $e) {
        // Handle error silently
    }
}

$pageTitle = 'Culinary Resources - FoodFusion';
include 'includes/header.php';

// Get current section
$section = $_GET['section'] ?? 'overview';

// Initialize data arrays
$recipes = [];
$videoTutorials = [];
$instructionalVideos = [];
$kitchenHacks = [];

// Get data based on section
try {
    global $db;
    
    // Get recipes for recipe cards (all recipes can be downloaded)
    $stmt = $db->prepare("SELECT r.*, u.firstName, u.lastName, ct.name as cuisine_name,
                          (SELECT COUNT(*) FROM recipe_ingredients ri WHERE ri.recipe_id = r.id) as ingredient_count
                          FROM recipes r 
                          JOIN users u ON r.user_id = u.id 
                          LEFT JOIN cuisine_types ct ON r.cuisine_type_id = ct.id
                          ORDER BY r.created_at DESC");
    $stmt->execute();
    $recipes = $stmt->fetchAll();
    
    // Get recipes with videos for cooking tutorials
    $stmt = $db->prepare("SELECT r.*, u.firstName, u.lastName, ct.name as cuisine_name
                          FROM recipes r 
                          JOIN users u ON r.user_id = u.id 
                          LEFT JOIN cuisine_types ct ON r.cuisine_type_id = ct.id
                          WHERE r.video_url IS NOT NULL AND r.video_url != ''
                          ORDER BY r.created_at DESC");
    $stmt->execute();
    $videoTutorials = $stmt->fetchAll();
    
    // Get educational resources for instructional videos
    $stmt = $db->prepare("SELECT er.*, u.firstName, u.lastName 
                          FROM educational_resources er 
                          LEFT JOIN users u ON er.created_by = u.id 
                          WHERE er.type = 'video'
                          ORDER BY er.created_at DESC");
    $stmt->execute();
    $instructionalVideos = $stmt->fetchAll();
    
    // Get cooking tips for kitchen hacks
    $stmt = $db->prepare("SELECT ct.*, u.firstName, u.lastName, u.profile_image 
                          FROM cooking_tips ct 
                          JOIN users u ON ct.user_id = u.id 
                          ORDER BY ct.created_at DESC");
    $stmt->execute();
    $kitchenHacks = $stmt->fetchAll();
    
} catch (Exception $e) {
    // Handle error silently
}

// Helper function to generate recipe card content
function generateRecipeCard($recipe, $ingredients) {
    $content = "=== " . strtoupper($recipe['title']) . " ===\n\n";
    $content .= "Description: " . $recipe['description'] . "\n\n";
    $content .= "Cooking Time: " . $recipe['cooking_time'] . " minutes\n";
    $content .= "Difficulty: " . $recipe['difficulty'] . "\n";
    $content .= "Servings: " . $recipe['servings'] . "\n\n";
    
    $content .= "INGREDIENTS:\n";
    $content .= "============\n";
    foreach ($ingredients as $ingredient) {
        $content .= "- " . $ingredient['quantity'] . " " . $ingredient['unit'] . " " . $ingredient['name'] . "\n";
    }
    
    $content .= "\nINSTRUCTIONS:\n";
    $content .= "=============\n";
    $content .= $recipe['instructions'] . "\n\n";
    
    $content .= "Recipe by: " . $recipe['firstName'] . " " . $recipe['lastName'] . "\n";
    $content .= "Downloaded from FoodFusion\n";
    
    return $content;
}

// Helper function to sanitize filename
function sanitizeFilename($filename) {
    return preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
}
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Culinary Resources</h1>
                <p class="text-xl text-green-100 mb-8">Downloadable recipe cards, cooking tutorials, instructional videos, and kitchen hacks</p>
                
                <!-- Navigation Tabs -->
                <div class="flex flex-wrap justify-center space-x-1 bg-white/10 rounded-lg p-1 max-w-4xl mx-auto">
                    <a href="?page=culinary&section=overview" 
                       class="px-6 py-3 rounded-md font-medium transition-colors <?php echo $section === 'overview' ? 'bg-white text-green-600' : 'text-white hover:bg-white/20'; ?>">
                        <i class="fas fa-home mr-2"></i>Overview
                    </a>
                    <a href="?page=culinary&section=recipe-cards" 
                       class="px-6 py-3 rounded-md font-medium transition-colors <?php echo $section === 'recipe-cards' ? 'bg-white text-green-600' : 'text-white hover:bg-white/20'; ?>">
                        <i class="fas fa-file-alt mr-2"></i>Recipe Cards
                    </a>
                    <a href="?page=culinary&section=tutorials" 
                       class="px-6 py-3 rounded-md font-medium transition-colors <?php echo $section === 'tutorials' ? 'bg-white text-green-600' : 'text-white hover:bg-white/20'; ?>">
                        <i class="fas fa-play mr-2"></i>Video Tutorials
                    </a>
                    <a href="?page=culinary&section=videos" 
                       class="px-6 py-3 rounded-md font-medium transition-colors <?php echo $section === 'videos' ? 'bg-white text-green-600' : 'text-white hover:bg-white/20'; ?>">
                        <i class="fas fa-video mr-2"></i>Educational Videos
                    </a>
                    <a href="?page=culinary&section=kitchen-hacks" 
                       class="px-6 py-3 rounded-md font-medium transition-colors <?php echo $section === 'kitchen-hacks' ? 'bg-white text-green-600' : 'text-white hover:bg-white/20'; ?>">
                        <i class="fas fa-lightbulb mr-2"></i>Kitchen Hacks
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if ($section === 'overview'): ?>
            <!-- Overview Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Recipe Cards</h3>
                    <p class="text-3xl font-bold text-blue-600 mb-2"><?php echo count($recipes); ?></p>
                    <p class="text-gray-600 text-sm">Downloadable recipes</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-play text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Video Tutorials</h3>
                    <p class="text-3xl font-bold text-green-600 mb-2"><?php echo count($videoTutorials); ?></p>
                    <p class="text-gray-600 text-sm">Recipe videos</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-video text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Educational Videos</h3>
                    <p class="text-3xl font-bold text-red-600 mb-2"><?php echo count($instructionalVideos); ?></p>
                    <p class="text-gray-600 text-sm">Learning resources</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lightbulb text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Kitchen Tips</h3>
                    <p class="text-3xl font-bold text-yellow-600 mb-2"><?php echo count($kitchenHacks); ?></p>
                    <p class="text-gray-600 text-sm">Cooking wisdom</p>
                </div>
            </div>
        
        <?php elseif ($section === 'recipe-cards'): ?>
            <!-- Recipe Cards Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Downloadable Recipe Cards</h2>
                <p class="text-gray-600 mb-8">Download recipes as text files for your personal collection.</p>
                
                <?php if (!empty($recipes)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($recipes as $recipe): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars(substr($recipe['description'], 0, 100)) . '...'; ?></p>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span><i class="fas fa-user mr-1"></i>by <?php echo htmlspecialchars($recipe['firstName'] . ' ' . $recipe['lastName']); ?></span>
                                    <span><i class="fas fa-clock mr-1"></i><?php echo $recipe['cooking_time']; ?> min</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded"><?php echo $recipe['difficulty']; ?></span>
                                    <a href="?page=culinary&download=1&type=recipe&id=<?php echo $recipe['id']; ?>" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No recipes available</h3>
                    <p class="text-gray-600">Check back soon for downloadable recipes!</p>
                </div>
                <?php endif; ?>
            </div>
        
        <?php elseif ($section === 'tutorials'): ?>
            <!-- Video Tutorials Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Video Tutorials</h2>
                <p class="text-gray-600 mb-8">Watch recipe videos to learn cooking techniques.</p>
                
                <?php if (!empty($videoTutorials)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($videoTutorials as $tutorial): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="relative h-48 bg-gray-900 flex items-center justify-center">
                            <?php if (strpos($tutorial['video_url'], 'uploads/') === 0): ?>
                            <video class="w-full h-full object-cover" controls>
                                <source src="<?php echo htmlspecialchars($tutorial['video_url']); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <?php else: ?>
                            <div class="text-center text-white">
                                <i class="fas fa-play-circle text-6xl mb-2"></i>
                                <p class="text-sm">Video Tutorial</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($tutorial['title']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars(substr($tutorial['description'], 0, 100)) . '...'; ?></p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span><i class="fas fa-user mr-1"></i>by <?php echo htmlspecialchars($tutorial['firstName'] . ' ' . $tutorial['lastName']); ?></span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded"><?php echo $tutorial['difficulty']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-play text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No video tutorials available</h3>
                    <p class="text-gray-600">Check back soon for recipe videos!</p>
                </div>
                <?php endif; ?>
            </div>
        
        <?php elseif ($section === 'videos'): ?>
            <!-- Educational Videos Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Educational Videos</h2>
                <p class="text-gray-600 mb-8">Educational resources and instructional content.</p>
                
                <?php if (!empty($instructionalVideos)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($instructionalVideos as $video): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-video text-2xl text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($video['title']); ?></h3>
                                <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($video['description']); ?></p>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <?php if ($video['firstName']): ?>
                                    <span><i class="fas fa-user mr-1"></i>by <?php echo htmlspecialchars($video['firstName'] . ' ' . $video['lastName']); ?></span>
                                    <?php endif; ?>
                                    <span><i class="fas fa-download mr-1"></i><?php echo $video['download_count']; ?> downloads</span>
                                </div>
                                
                                <a href="?page=culinary&download=1&type=educational_resource&id=<?php echo $video['id']; ?>" 
                                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-video text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No educational videos available</h3>
                    <p class="text-gray-600">Check back soon for educational content!</p>
                </div>
                <?php endif; ?>
            </div>
        
        <?php elseif ($section === 'kitchen-hacks'): ?>
            <!-- Kitchen Hacks Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Kitchen Hacks & Cooking Tips</h2>
                <p class="text-gray-600 mb-8">Discover helpful cooking tips and kitchen wisdom from our community.</p>
                
                <?php if (!empty($kitchenHacks)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($kitchenHacks as $tip): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lightbulb text-yellow-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($tip['title']); ?></h3>
                                <p class="text-gray-700 mb-4"><?php echo nl2br(htmlspecialchars($tip['content'])); ?></p>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <span><i class="fas fa-user mr-1"></i>by <?php echo htmlspecialchars($tip['firstName'] . ' ' . $tip['lastName']); ?></span>
                                        <span><i class="fas fa-calendar mr-1"></i><?php echo formatDate($tip['created_at']); ?></span>
                                    </div>
                                    <?php if ($tip['prep_time']): ?>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                                        <i class="fas fa-clock mr-1"></i><?php echo $tip['prep_time']; ?> min
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-lightbulb text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No cooking tips available</h3>
                    <p class="text-gray-600">Check back soon for kitchen hacks and tips!</p>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleTutorial(id) {
    const tutorial = document.getElementById('tutorial-' + id);
    tutorial.classList.toggle('hidden');
}
</script>

