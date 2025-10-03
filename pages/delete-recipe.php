<?php
$pageTitle = 'Delete Recipe - FoodFusion';
include 'includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

// Require login
requireLogin();

// Get recipe ID
$recipeId = $_GET['id'] ?? 0;
if (!$recipeId) {
    redirect('index.php?page=recipes');
}

// Get recipe details
$recipe = getRecipeById($recipeId);
if (!$recipe) {
    redirect('index.php?page=recipes');
}

// Check if user owns this recipe
if ($recipe['user_id'] != $user['id']) {
    redirect('index.php?page=recipe-detail&id=' . $recipeId);
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    if (deleteRecipe($recipeId, $user['id'])) {
        $_SESSION['toast_message'] = 'Recipe deleted successfully!';
        $_SESSION['toast_type'] = 'success';
        echo '<script>location.assign("index.php?page=recipes");</script>';
        exit;
    } else {
        $error = 'Failed to delete recipe. Please try again.';
    }
}
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Delete Recipe</h1>
                
                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete "<strong><?php echo htmlspecialchars($recipe['title']); ?></strong>"? 
                    This action cannot be undone and will permanently remove the recipe and all its data.
                </p>
                
                <?php if (isset($error)): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md mb-6">
                    <div class="flex justify-center">
                        <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="flex items-center justify-center space-x-4">
                    <a href="index.php?page=recipe-detail&id=<?php echo $recipeId; ?>" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">
                        Cancel
                    </a>
                    <form method="POST" class="inline">
                        <button type="submit" name="confirm_delete" 
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-medium">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Recipe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
