<?php
$pageTitle = 'Community - FoodFusion';
include 'includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

// Get community stats and tips
$totalUsers = 0;
$totalRecipes = 0;
$totalTips = 0;
$recentUsers = [];
$cookingTips = [];
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

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
    
    // Get cooking tips with like information
    $tipsQuery = "SELECT ct.*, u.firstName, u.lastName, u.profile_image,
                         COALESCE(tl.user_liked, 0) as is_liked,
                         COALESCE(like_counts.like_count, 0) as like_count
                  FROM cooking_tips ct 
                  LEFT JOIN users u ON ct.user_id = u.id
                  LEFT JOIN (
                      SELECT tip_id, 1 as user_liked 
                      FROM tip_likes 
                      WHERE user_id = :current_user_id
                  ) tl ON ct.id = tl.tip_id
                  LEFT JOIN (
                      SELECT tip_id, COUNT(*) as like_count
                      FROM tip_likes
                      GROUP BY tip_id
                  ) like_counts ON ct.id = like_counts.tip_id
                  ORDER BY ct.created_at DESC
                  LIMIT 20";
    
    $stmt = $db->prepare($tipsQuery);
    $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $cookingTips = $stmt->fetchAll();
    
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
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
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
                
            </div>
        </div>
    </section>



    <!-- Cooking Tips Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cooking Tips & Knowledge Sharing</h2>
                <p class="text-lg text-gray-600">Share your culinary wisdom and learn from fellow cooks</p>
            </div>
            
            <!-- Share Tips Button and Form (for logged-in users) -->
            <?php if ($currentUserId): ?>
            <div class="mb-12">
                <!-- Share Tips Button -->
                <div class="text-center mb-6">
                    <button id="shareTipsBtn" onclick="toggleTipForm()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Share Tips
                    </button>
                </div>
                
                <!-- Tip Creation Form (hidden by default) -->
                <div id="tipFormContainer" class="hidden">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Share a Cooking Tip</h3>
                        <form id="tipForm" class="space-y-4">
                            <div>
                                <label for="tipTitle" class="block text-sm font-medium text-gray-700 mb-2">Tip Title</label>
                                <input type="text" id="tipTitle" name="title" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="e.g., Perfect Rice Every Time">
                            </div>
                            <div>
                                <label for="tipContent" class="block text-sm font-medium text-gray-700 mb-2">Tip Content</label>
                                <textarea id="tipContent" name="content" rows="4" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                          placeholder="Share your cooking wisdom..."></textarea>
                            </div>
                            <div>
                                <label for="tipPrepTime" class="block text-sm font-medium text-gray-700 mb-2">Prep Time (minutes)</label>
                                <input type="number" id="tipPrepTime" name="prep_time" min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Optional">
                            </div>
                            <div class="flex space-x-4">
                                <button type="submit" id="tipSubmitBtn"
                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span id="tipSubmitText">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        Share Tip
                                    </span>
                                    <span id="tipSubmitLoading" class="hidden">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Sharing...
                                    </span>
                                </button>
                                <button type="button" onclick="toggleTipForm()" 
                                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Tips Display -->
            <div class="space-y-6">
                <?php if (!empty($cookingTips)): ?>
                    <?php foreach ($cookingTips as $tip): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <?php if ($tip['profile_image']): ?>
                                    <img src="uploads/<?php echo $tip['profile_image']; ?>" 
                                         alt="Profile" class="w-10 h-10 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($tip['firstName'] . ' ' . $tip['lastName']); ?></h4>
                                    <p class="text-sm text-gray-500"><?php echo formatDate($tip['created_at']); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <?php if ($tip['prep_time']): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo $tip['prep_time']; ?> min
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3"><?php echo htmlspecialchars($tip['title']); ?></h3>
                        <p class="text-gray-700 mb-4 whitespace-pre-wrap"><?php echo htmlspecialchars($tip['content']); ?></p>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <button onclick="toggleTipLike(<?php echo $tip['id']; ?>)" 
                                        class="flex items-center space-x-2 text-gray-600 hover:text-red-500 transition-colors <?php echo $tip['is_liked'] ? 'text-red-500' : ''; ?>">
                                    <i class="fas fa-heart <?php echo $tip['is_liked'] ? 'text-red-500' : ''; ?>"></i>
                                    <span id="tip-like-count-<?php echo $tip['id']; ?>"><?php echo $tip['like_count']; ?></span>
                                </button>
                            </div>
                            
                            <?php if ($currentUserId && $tip['user_id'] == $currentUserId): ?>
                            <div class="flex items-center space-x-2">
                                <button onclick="editTip(<?php echo $tip['id']; ?>)" 
                                        class="text-gray-600 hover:text-green-600 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTip(<?php echo $tip['id']; ?>)" 
                                        class="text-gray-600 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-lightbulb text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No cooking tips yet</h3>
                    <p class="text-gray-600">Be the first to share your culinary wisdom!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Edit Tip Modal -->
    <?php if ($currentUserId): ?>
    <div id="editTipModal" class="fixed inset-0 bg-gray-200/50 hidden z-50">
        <div class="min-h-full flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Cooking Tip</h3>
                    <button class="text-gray-500 hover:text-gray-700" onclick="closeEditModal()"><i class="fas fa-times"></i></button>
                </div>
                <form id="editTipForm" class="p-6 space-y-4">
                    <input type="hidden" id="editTipId">
                    <div>
                        <label for="editTipTitle" class="block text-sm font-medium text-gray-700 mb-2">Tip Title</label>
                        <input type="text" id="editTipTitle" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="editTipContent" class="block text-sm font-medium text-gray-700 mb-2">Tip Content</label>
                        <textarea id="editTipContent" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                    </div>
                    <div>
                        <label for="editTipPrepTime" class="block text-sm font-medium text-gray-700 mb-2">Prep Time (minutes)</label>
                        <input type="number" id="editTipPrepTime" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Optional">
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" id="editTipSaveBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <span id="editTipSaveText"><i class="fas fa-save mr-2"></i>Save Changes</span>
                            <span id="editTipSaving" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Saving...</span>
                        </button>
                        <button type="button" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50" onclick="closeEditModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Community Guidelines -->
    <section class="py-16 bg-gray-50">
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
                <?php if (!isset($_SESSION['user_id'])): ?>
                <button onclick="showSignupModal()" class="bg-white hover:bg-gray-100 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Join Now
                </button>
                <?php else: ?>
                <a href="index.php?page=recipes" class="bg-white hover:bg-gray-100 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                    <i class="fas fa-book-open mr-2"></i>
                    Explore Recipes
                </a>
                <?php endif; ?>
                <a href="index.php?page=recipes" class="bg-green-700 hover:bg-green-800 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-white">
                    <i class="fas fa-utensils mr-2"></i>
                    Browse Recipes
                </a>
            </div>
        </div>
    </section>
</div>

<script>
// Toggle tip form visibility
function toggleTipForm() {
    const formContainer = document.getElementById('tipFormContainer');
    const shareBtn = document.getElementById('shareTipsBtn');
    
    if (formContainer.classList.contains('hidden')) {
        formContainer.classList.remove('hidden');
        shareBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Cancel';
        shareBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
        shareBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
    } else {
        formContainer.classList.add('hidden');
        shareBtn.innerHTML = '<i class="fas fa-lightbulb mr-2"></i>Share Tips';
        shareBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        shareBtn.classList.add('bg-green-600', 'hover:bg-green-700');
    }
}

// Tip form handling
document.addEventListener('DOMContentLoaded', function() {
    const tipForm = document.getElementById('tipForm');
    const tipSubmitBtn = document.getElementById('tipSubmitBtn');
    const tipSubmitText = document.getElementById('tipSubmitText');
    const tipSubmitLoading = document.getElementById('tipSubmitLoading');

    if (tipForm) {
        tipForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Show loading state
            tipSubmitBtn.disabled = true;
            tipSubmitText.classList.add('hidden');
            tipSubmitLoading.classList.remove('hidden');

            // Get form data
            const formData = new FormData(tipForm);
            const data = {
                title: formData.get('title'),
                content: formData.get('content'),
                prep_time: formData.get('prep_time') || null
            };

            try {
                const response = await fetch('api/tip_create.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    // Show success message
                    showToast('Tip shared successfully!', 'success');
                    
                    // Reset form
                    tipForm.reset();
                    
                    // Hide form and reset button
                    toggleTipForm();
                    
                    // Reload page to show new tip
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(result.message || 'Failed to share tip', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while sharing your tip', 'error');
            } finally {
                // Reset button state
                tipSubmitBtn.disabled = false;
                tipSubmitText.classList.remove('hidden');
                tipSubmitLoading.classList.add('hidden');
            }
        });
    }
});

// Handle edit form submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editTipForm');
    if (!form) return;
    const saveBtn = document.getElementById('editTipSaveBtn');
    const saveText = document.getElementById('editTipSaveText');
    const saving = document.getElementById('editTipSaving');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        saveBtn.disabled = true;
        saveText.classList.add('hidden');
        saving.classList.remove('hidden');

        const payload = {
            tip_id: document.getElementById('editTipId').value,
            title: document.getElementById('editTipTitle').value.trim(),
            content: document.getElementById('editTipContent').value.trim(),
            prep_time: document.getElementById('editTipPrepTime').value || null
        };

        try {
            const res = await fetch('api/tip_update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (result.success) {
                showToast('Tip updated successfully!', 'success');
                closeEditModal();
                setTimeout(() => location.reload(), 800);
            } else {
                showToast(result.message || 'Failed to update tip', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('An error occurred while updating tip', 'error');
        } finally {
            saveBtn.disabled = false;
            saveText.classList.remove('hidden');
            saving.classList.add('hidden');
        }
    });
});

// Toggle tip like
async function toggleTipLike(tipId) {
    try {
        const response = await fetch('api/tip_like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ tip_id: tipId })
        });

        const result = await response.json();

        if (result.success) {
            // Update like count and button state
            const likeButton = document.querySelector(`button[onclick="toggleTipLike(${tipId})"]`);
            const likeCount = document.getElementById(`tip-like-count-${tipId}`);
            const heartIcon = likeButton.querySelector('i');
            
            if (result.isLiked) {
                likeButton.classList.add('text-red-500');
                heartIcon.classList.add('text-red-500');
            } else {
                likeButton.classList.remove('text-red-500');
                heartIcon.classList.remove('text-red-500');
            }
            
            likeCount.textContent = result.like_count;
        } else {
            showToast(result.message || 'Failed to update like', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while updating like', 'error');
    }
}

// Edit tip
function editTip(tipId) {
    const tipCard = document.querySelector(`button[onclick="editTip(${tipId})"]`)?.closest('.bg-white');
    if (!tipCard) return;
    const title = tipCard.querySelector('h3')?.textContent?.trim() || '';
    const content = tipCard.querySelector('p.text-gray-700')?.textContent?.trim() || '';
    const prepBadge = tipCard.querySelector('.fa-clock')?.parentElement?.textContent || '';
    const prepMatch = prepBadge.match(/(\d+)\s*min/);
    const prepTime = prepMatch ? parseInt(prepMatch[1], 10) : '';

    // Prefill modal
    document.getElementById('editTipId').value = tipId;
    document.getElementById('editTipTitle').value = title;
    document.getElementById('editTipContent').value = content;
    document.getElementById('editTipPrepTime').value = prepTime;

    // Show modal
    const modal = document.getElementById('editTipModal');
    modal.classList.remove('hidden');
}

function closeEditModal() {
    const modal = document.getElementById('editTipModal');
    if (modal) modal.classList.add('hidden');
}

// Delete tip
async function deleteTip(tipId) {
    if (!confirm('Are you sure you want to delete this tip?')) {
        return;
    }

    try {
        const response = await fetch('api/tip_delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ tip_id: tipId })
        });

        const result = await response.json();

        if (result.success) {
            showToast('Tip deleted successfully!', 'success');
            // Reload page to remove deleted tip
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast(result.message || 'Failed to delete tip', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while deleting tip', 'error');
    }
}
</script>

<?php include 'includes/footer.php'; ?>
