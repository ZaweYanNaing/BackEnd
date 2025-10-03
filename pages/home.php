<?php
$pageTitle = 'Home - FoodFusion';
include 'includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

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



<!-- Cooking Events Carousel -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Upcoming Cooking Events</h2>
            <?php if (!$isLoggedIn): ?>
            <p class="text-gray-600">Sign in to create your own community cooking event.</p>
            <?php else: ?>
            <button onclick="openCreateEventModal()" class="mt-2 inline-flex items-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                <i class="fas fa-plus mr-2"></i> Create Event
            </button>
            <?php endif; ?>
        </div>
        
        <div class="relative">
            <div class="overflow-hidden rounded-lg">
                <div class="flex transition-transform duration-500 ease-in-out" id="events-carousel">
                    <?php
                    // Load upcoming events from DB
                    $cookingEvents = [];
                    try {
                        global $db;
                        // Show upcoming events; include slight grace window to avoid timezone offsets
                        $stmt = $db->prepare("SELECT id, title, description, event_date, location, max_participants, current_participants FROM events WHERE event_date >= DATE_SUB(NOW(), INTERVAL 1 DAY) ORDER BY event_date ASC LIMIT 10");
                        $stmt->execute();
                        $cookingEvents = $stmt->fetchAll();
                        if (!$cookingEvents) {
                            // Fallback to most recent events if none upcoming
                            $stmt = $db->prepare("SELECT id, title, description, event_date, location, max_participants, current_participants FROM events ORDER BY event_date DESC LIMIT 10");
                            $stmt->execute();
                            $cookingEvents = $stmt->fetchAll();
                        }
                    } catch (Exception $e) {
                        $cookingEvents = [];
                    }
                    ?>
                    <?php if (empty($cookingEvents)): ?>
                    <div class="w-full flex-shrink-0 block" data-event-index="0">
                        <div class="bg-gray-50 rounded-lg p-8">
                            <div class="text-center text-gray-600">No events yet. <?php echo $isLoggedIn ? 'Be the first to create one!' : 'Sign in to create an event.'; ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php foreach ($cookingEvents as $index => $event): ?>
                    <div class="w-full flex-shrink-0 <?php echo $index === 0 ? 'block' : 'hidden'; ?>" data-event-index="<?php echo $index; ?>">
                        <div class="bg-gray-50 rounded-lg p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                                <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-calendar-alt text-6xl text-green-600 mb-2"></i>
                                        <div class="text-gray-500">Community Cooking Event</div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($event['description']); ?></p>
                                    
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-calendar w-5 h-5 mr-3 text-[#78C841]"></i>
                                            <span><?php echo date('l, F j, Y g:i A', strtotime($event['event_date'])); ?></span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-map-marker-alt w-5 h-5 mr-3 text-[#B4E50D]"></i>
                                            <span><?php echo htmlspecialchars($event['location']); ?></span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-users w-5 h-5 mr-3 text-[#FF9B2F]"></i>
                                            <span><?php echo (int)$event['current_participants']; ?>/<?php echo (int)$event['max_participants']; ?> participants</span>
                                        </div>
                                    </div>
                                    
                                    <?php if ($isLoggedIn): ?>
                                    <button onclick="registerForEvent(<?php echo (int)$event['id']; ?>)" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                                        Register Now
                                    </button>
                                    <?php else: ?>
                                    <button onclick="showLoginModal()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                                        Sign in to Register
                                    </button>
                                    <?php endif; ?>
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

<?php if ($isLoggedIn): ?>
<!-- Create Event Modal -->
<div id="createEventModal" class="fixed inset-0 bg-gray-200/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Create Event</h3>
                <button class="text-gray-500 hover:text-gray-700" onclick="closeCreateEventModal()"><i class="fas fa-times"></i></button>
            </div>
            <form id="createEventForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" id="eventTitle" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="eventDescription" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                        <input type="datetime-local" id="eventDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Participants</label>
                        <input type="number" id="eventMax" min="0" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" id="eventLocation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" />
                </div>
                
                <div id="createEventMsg" class="hidden text-sm p-2 rounded-md"></div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg" onclick="closeCreateEventModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Create</button>
                </div>
            </form>
        </div>
    </div>
    </div>
<?php endif; ?>

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
                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM recipe_reviews");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        echo number_format($result['count']);
                    } catch (Exception $e) {
                        echo '0';
                    }
                    ?>
                </div>
                <div class="text-green-200">Total Reviews</div>
            </div>
        </div>
    </div>
</section>

<script>
// Events carousel functionality
let currentEventIndex = 0;
const totalEvents = <?php echo (int)count($cookingEvents); ?>;

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
if (totalEvents > 1) {
    setInterval(nextEvent, 5000);
}

<?php if ($isLoggedIn): ?>
// Create Event Modal handlers
function openCreateEventModal(){ document.getElementById('createEventModal').classList.remove('hidden'); }
function closeCreateEventModal(){ document.getElementById('createEventModal').classList.add('hidden'); }

document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('createEventForm');
    if (!form) return;
    const msg = document.getElementById('createEventMsg');
    form.addEventListener('submit', async function(e){
        e.preventDefault();
        msg.classList.add('hidden');
        const payload = {
            title: document.getElementById('eventTitle').value.trim(),
            description: document.getElementById('eventDescription').value.trim(),
            event_date: document.getElementById('eventDate').value,
            location: document.getElementById('eventLocation').value.trim(),
            max_participants: parseInt(document.getElementById('eventMax').value || '0', 10)
        };
        try{
            const res = await fetch('api/event_create.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
            const json = await res.json();
            if (json.success){
                msg.className = 'text-sm p-2 rounded-md bg-green-50 text-green-700';
                msg.textContent = 'Event created successfully!';
                msg.classList.remove('hidden');
                setTimeout(()=>{ window.location.reload(); }, 800);
            } else {
                msg.className = 'text-sm p-2 rounded-md bg-red-50 text-red-700';
                msg.textContent = json.message || 'Failed to create event';
                msg.classList.remove('hidden');
            }
        } catch(err){
            msg.className = 'text-sm p-2 rounded-md bg-red-50 text-red-700';
            msg.textContent = 'An error occurred';
            msg.classList.remove('hidden');
        }
    });
});
// Register for event
async function registerForEvent(eventId){
    try{
        const res = await fetch('api/event_register.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ event_id: eventId }) });
        const json = await res.json();
        showToast(json.message || (json.success ? 'Registered' : 'Failed'), json.success ? 'success' : 'error');
        if (json.success){ setTimeout(()=>location.reload(), 800); }
    } catch(e){ showToast('Registration failed', 'error'); }
}
<?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>
