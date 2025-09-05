<?php
$pageTitle = 'Cooking Tips - FoodFusion';
include 'includes/header.php';

// Get cooking tips
$tips = [];
try {
    global $db;
    $stmt = $db->prepare("SELECT ct.*, u.firstName, u.lastName, u.profile_image 
                          FROM cooking_tips ct 
                          JOIN users u ON ct.user_id = u.id 
                          ORDER BY ct.created_at DESC");
    $stmt->execute();
    $tips = $stmt->fetchAll();
} catch (Exception $e) {
    // Handle error silently
}

// Handle tip creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $isLoggedIn) {
    $title = sanitizeInput($_POST['title'] ?? '');
    $content = sanitizeInput($_POST['content'] ?? '');
    $prep_time = $_POST['prep_time'] ?? '';
    
    if (!empty($title) && !empty($content)) {
        try {
            global $db;
            $stmt = $db->prepare("INSERT INTO cooking_tips (title, content, user_id, prep_time, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $content, $user['id'], $prep_time ? (int)$prep_time : null]);
            
            $_SESSION['toast_message'] = 'Cooking tip created successfully!';
            $_SESSION['toast_type'] = 'success';
            redirect('index.php?page=cooking-tips');
        } catch (Exception $e) {
            $error = 'Failed to create cooking tip. Please try again.';
        }
    } else {
        $error = 'Title and content are required.';
    }
}
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Cooking Tips</h1>
                    <p class="mt-2 text-gray-600">Learn from our community's cooking wisdom and techniques</p>
                </div>
                
                <?php if ($isLoggedIn): ?>
                <div class="mt-4 md:mt-0">
                    <button onclick="toggleTipForm()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Share a Tip
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Tip Form (Hidden by default) -->
    <?php if ($isLoggedIn): ?>
    <div id="tip-form" class="hidden bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="POST" class="space-y-6">
                <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Tip Title *
                    </label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="e.g., Perfect Rice Every Time">
                </div>
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Tip Content *
                    </label>
                    <textarea id="content" name="content" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Share your cooking wisdom..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="prep_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Prep Time (minutes)
                        </label>
                        <input type="number" id="prep_time" name="prep_time" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-4">
                    <button type="button" onclick="toggleTipForm()" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Share Tip
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tips Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if (empty($tips)): ?>
        <div class="text-center py-12">
            <i class="fas fa-lightbulb text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No cooking tips yet</h3>
            <p class="text-gray-600">Be the first to share a cooking tip with our community!</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($tips as $tip): ?>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-start mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-lightbulb text-green-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                            <?php echo htmlspecialchars($tip['title']); ?>
                        </h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <img src="<?php echo $tip['profile_image'] ? 'uploads/' . $tip['profile_image'] : 'https://via.placeholder.com/24x24/78C841/FFFFFF?text=' . substr($tip['firstName'], 0, 1); ?>" 
                                 alt="Profile" class="w-6 h-6 rounded-full mr-2">
                            <span>by <?php echo htmlspecialchars($tip['firstName'] . ' ' . $tip['lastName']); ?></span>
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-700 mb-4">
                    <?php echo nl2br(htmlspecialchars($tip['content'])); ?>
                </p>
                
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center space-x-4">
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
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleTipForm() {
    const form = document.getElementById('tip-form');
    form.classList.toggle('hidden');
    
    if (!form.classList.contains('hidden')) {
        document.getElementById('title').focus();
    }
}
</script>

<?php include 'includes/footer.php'; ?>
