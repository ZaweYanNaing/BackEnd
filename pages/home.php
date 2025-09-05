<?php
$pageTitle = 'Home - FoodFusion';
include 'includes/header.php';

// Get recent recipes
$recentRecipes = getAllRecipes(['limit' => 6]);

// Get trending recipes (most liked in the last week)
$trendingRecipes = getAllRecipes(['trending' => true, 'limit' => 3]);

// Get cooking tips
$cookingTips = [];
try {
    global $db;
    $stmt = $db->prepare("SELECT ct.*, u.firstName, u.lastName 
                          FROM cooking_tips ct 
                          JOIN users u ON ct.user_id = u.id 
                          ORDER BY ct.created_at DESC 
                          LIMIT 3");
    $stmt->execute();
    $cookingTips = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error silently
}
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-emerald-100 to-teal-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
            Welcome to{' '}
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-green-600">
                FoodFusion
            </span>
        </h1>
        <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
            A culinary platform dedicated to promoting home cooking and culinary 
            creativity among food enthusiasts. Share recipes, learn techniques, 
            and connect with fellow cooking enthusiasts.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <?php if (!$isLoggedIn): ?>
            <button onclick="showSignupModal()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                <i class="fas fa-user-plus mr-2"></i>
                Sign up Now
            </button>
            <button onclick="showLoginModal()" class="bg-white hover:bg-gray-50 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-green-600">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In
            </button>
            <?php endif; ?>
            <a href="index.php?page=recipes" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                <i class="fas fa-book-open mr-2"></i>
                Explore Recipes
            </a>
            <a href="index.php?page=search" class="bg-white hover:bg-gray-50 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-green-600">
                <i class="fas fa-search mr-2"></i>
                Recipe Discovery
            </a>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Our Mission
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                At FoodFusion, we believe that cooking is more than just preparing food—it's an art, 
                a science, and a way to bring people together. Our mission is to inspire and empower 
                home cooks of all skill levels to explore new flavors, master cooking techniques, and 
                create memorable meals that nourish both body and soul.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-[#78C841]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🍳</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Learn & Grow</h3>
                <p class="text-gray-600">
                    Access comprehensive cooking tutorials, tips, and techniques from culinary experts
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-[#B4E50D]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">👥</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Connect & Share</h3>
                <p class="text-gray-600">
                    Join a vibrant community of food lovers and share your culinary creations
                </p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-[#FF9B2F]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🌟</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Discover & Inspire</h3>
                <p class="text-gray-600">
                    Explore diverse cuisines and recipes from around the world
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Trending Recipes Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Discover Amazing Recipes
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Explore trending, popular, and recently added recipes from our community
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Trending Recipes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-trending-up w-6 h-6 text-[#78C841] mr-2"></i>
                    <h3 class="text-xl font-semibold text-gray-900">Trending Now</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Most popular recipes this week based on views, ratings, and likes
                </p>
                <a href="index.php?page=recipes&filter=trending" 
                   class="w-full bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-300 font-medium inline-flex items-center justify-center">
                    View Trending
                </a>
            </div>

            <!-- Popular Recipes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-fire w-6 h-6 text-orange-500 mr-2"></i>
                    <h3 class="text-xl font-semibold text-gray-900">Most Popular</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Highest-rated and most-liked recipes from our community
                </p>
                <a href="index.php?page=recipes&filter=popular" 
                   class="w-full bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-300 font-medium inline-flex items-center justify-center">
                    View Popular
                </a>
            </div>

            <!-- Recent Recipes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-clock w-6 h-6 text-blue-500 mr-2"></i>
                    <h3 class="text-xl font-semibold text-gray-900">Recently Added</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Fresh recipes just added by our community members
                </p>
                <a href="index.php?page=recipes&filter=recent" 
                   class="w-full bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-300 font-medium inline-flex items-center justify-center">
                    View Recent
                </a>
            </div>
        </div>
    </div>
</section>

<!-- News Feed Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Latest News & Updates
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            // Sample news feed items
            $newsFeedItems = [
                [
                    'id' => 1,
                    'title' => "New Seasonal Recipes for Spring",
                    'content' => "Discover fresh ingredients and vibrant flavors with our latest spring recipe collection.",
                    'imageUrl' => "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop",
                    'type' => "recipe_feature",
                    'date' => "2025-01-15"
                ],
                [
                    'id' => 2,
                    'title' => "Master the Art of Sourdough Bread",
                    'content' => "Learn the secrets of perfect sourdough from our community experts.",
                    'imageUrl' => "https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=400&h=300&fit=crop",
                    'type' => "cooking_tip",
                    'date' => "2025-01-14"
                ],
                [
                    'id' => 3,
                    'title' => "Upcoming Cooking Workshop: Asian Cuisine",
                    'content' => "Join us for an exciting workshop on traditional Asian cooking techniques.",
                    'imageUrl' => "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop",
                    'type' => "event_announcement",
                    'date' => "2025-01-13"
                ]
            ];
            ?>
            <?php foreach ($newsFeedItems as $item): ?>
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <img 
                    src="<?php echo $item['imageUrl']; ?>" 
                    alt="<?php echo htmlspecialchars($item['title']); ?>"
                    class="w-full h-48 object-cover"
                />
                <div class="p-6">
                    <div class="flex items-center mb-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full <?php 
                            echo $item['type'] === 'recipe_feature' ? 'bg-[#78C841]/20 text-[#78C841]' :
                            ($item['type'] === 'cooking_tip' ? 'bg-[#B4E50D]/20 text-[#B4E50D]' :
                            ($item['type'] === 'event_announcement' ? 'bg-purple-100 text-purple-800' :
                            'bg-gray-100 text-gray-800'));
                        ?>">
                            <?php echo ucwords(str_replace('_', ' ', $item['type'])); ?>
                        </span>
                        <span class="text-sm text-gray-500 ml-auto"><?php echo $item['date']; ?></span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($item['content']); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Cooking Events Carousel -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Upcoming Cooking Events
        </h2>
        
        <div class="relative">
            <div class="overflow-hidden rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="events-carousel">
                    <?php
                    // Sample cooking events
                    $cookingEvents = [
                        [
                            'id' => 1,
                            'title' => "Italian Pasta Making Workshop",
                            'description' => "Learn to make authentic Italian pasta from scratch with Chef Maria Rossi",
                            'date' => "2025-02-15",
                            'location' => "FoodFusion Kitchen Studio",
                            'imageUrl' => "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop",
                            'maxParticipants' => 20,
                            'currentParticipants' => 15
                        ],
                        [
                            'id' => 2,
                            'title' => "Sushi Rolling Masterclass",
                            'description' => "Master the art of sushi making with traditional techniques",
                            'date' => "2025-02-22",
                            'location' => "FoodFusion Kitchen Studio",
                            'imageUrl' => "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop",
                            'maxParticipants' => 15,
                            'currentParticipants' => 8
                        ],
                        [
                            'id' => 3,
                            'title' => "Baking Fundamentals",
                            'description' => "Learn essential baking techniques and create delicious pastries",
                            'date' => "2025-03-01",
                            'location' => "FoodFusion Kitchen Studio",
                            'imageUrl' => "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop",
                            'maxParticipants' => 25,
                            'currentParticipants' => 22
                        ]
                    ];
                    ?>
                    <?php foreach ($cookingEvents as $index => $event): ?>
                    <div class="w-full flex-shrink-0 <?php echo $index === 0 ? 'block' : 'hidden'; ?>" data-event-index="<?php echo $index; ?>">
                        <div class="bg-gray-50 rounded-lg p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                <div>
                                    <img 
                                        src="<?php echo $event['imageUrl']; ?>" 
                                        alt="<?php echo htmlspecialchars($event['title']); ?>"
                                        class="w-full h-64 object-cover rounded-lg"
                                    />
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($event['description']); ?></p>
                                    
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-calendar w-5 h-5 mr-3 text-[#78C841]"></i>
                                            <span><?php echo date('l, F j, Y', strtotime($event['date'])); ?></span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-map-marker-alt w-5 h-5 mr-3 text-[#B4E50D]"></i>
                                            <span><?php echo htmlspecialchars($event['location']); ?></span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-users w-5 h-5 mr-3 text-[#FF9B2F]"></i>
                                            <span><?php echo $event['currentParticipants']; ?>/<?php echo $event['maxParticipants']; ?> participants</span>
                                        </div>
                                    </div>
                                    
                                    <button class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                                        Register Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Navigation Arrows -->
            <button
                onclick="prevEvent()"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition-colors"
            >
                <i class="fas fa-chevron-left w-6 h-6 text-gray-600"></i>
            </button>
            <button
                onclick="nextEvent()"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition-colors"
            >
                <i class="fas fa-chevron-right w-6 h-6 text-gray-600"></i>
            </button>
            
            <!-- Dots Indicator -->
            <div class="flex justify-center mt-6 space-x-2">
                <?php foreach ($cookingEvents as $index => $event): ?>
                <button
                    onclick="setCurrentEvent(<?php echo $index; ?>)"
                    class="w-3 h-3 rounded-full transition-colors <?php echo $index === 0 ? 'bg-[#78C841]' : 'bg-gray-300'; ?>"
                    data-dot-index="<?php echo $index; ?>"
                ></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-green-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold mb-2">
                    <?php
                    try {
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM recipes");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        echo number_format($result['count']);
                    } catch (Exception $e) {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="text-green-200">Recipes</div>
            </div>
            <div>
                <div class="text-4xl font-bold mb-2">
                    <?php
                    try {
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM users");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        echo number_format($result['count']);
                    } catch (Exception $e) {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="text-green-200">Community Members</div>
            </div>
            <div>
                <div class="text-4xl font-bold mb-2">
                    <?php
                    try {
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM cooking_tips");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        echo number_format($result['count']);
                    } catch (Exception $e) {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="text-green-200">Cooking Tips</div>
            </div>
            <div>
                <div class="text-4xl font-bold mb-2">
                    <?php
                    try {
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM recipe_views");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        echo number_format($result['count']);
                    } catch (Exception $e) {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="text-green-200">Recipe Views</div>
            </div>
        </div>
    </div>
</section>

<script>
// Events carousel functionality
let currentEventIndex = 0;
const totalEvents = <?php echo count($cookingEvents); ?>;

function nextEvent() {
    currentEventIndex = (currentEventIndex + 1) % totalEvents;
    setCurrentEvent(currentEventIndex);
}

function prevEvent() {
    currentEventIndex = (currentEventIndex - 1 + totalEvents) % totalEvents;
    setCurrentEvent(currentEventIndex);
}

function setCurrentEvent(index) {
    currentEventIndex = index;
    
    // Hide all events
    document.querySelectorAll('[data-event-index]').forEach(event => {
        event.classList.add('hidden');
        event.classList.remove('block');
    });
    
    // Show current event
    const currentEvent = document.querySelector(`[data-event-index="${index}"]`);
    if (currentEvent) {
        currentEvent.classList.remove('hidden');
        currentEvent.classList.add('block');
    }
    
    // Update dots
    document.querySelectorAll('[data-dot-index]').forEach((dot, dotIndex) => {
        if (dotIndex === index) {
            dot.classList.remove('bg-gray-300');
            dot.classList.add('bg-[#78C841]');
        } else {
            dot.classList.remove('bg-[#78C841]');
            dot.classList.add('bg-gray-300');
        }
    });
}

// Auto-advance events every 5 seconds
setInterval(nextEvent, 5000);
</script>

<?php include 'includes/footer.php'; ?>
