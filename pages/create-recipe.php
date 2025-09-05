<?php
$pageTitle = 'Create Recipe - FoodFusion';
include 'includes/header.php';

// Require login
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $instructions = sanitizeInput($_POST['instructions'] ?? '');
    $cooking_time = $_POST['cooking_time'] ?? '';
    $difficulty = $_POST['difficulty'] ?? 'Medium';
    $servings = $_POST['servings'] ?? '';
    $cuisine_type = $_POST['cuisine_type'] ?? '';
    $categories = $_POST['categories'] ?? [];
    $ingredients = $_POST['ingredients'] ?? [];
    
    // Validation
    if (empty($title) || empty($instructions)) {
        $error = 'Title and instructions are required.';
    } elseif (empty($ingredients)) {
        $error = 'At least one ingredient is required.';
    } else {
        // Process ingredients
        $processedIngredients = [];
        foreach ($ingredients as $ingredient) {
            if (!empty($ingredient['name'])) {
                $processedIngredients[] = [
                    'name' => sanitizeInput($ingredient['name']),
                    'quantity' => sanitizeInput($ingredient['quantity']),
                    'unit' => sanitizeInput($ingredient['unit'])
                ];
            }
        }
        
        if (empty($processedIngredients)) {
            $error = 'At least one ingredient is required.';
        } else {
            // Get cuisine type ID
            $cuisineTypeId = null;
            if (!empty($cuisine_type)) {
                try {
                    global $db;
                    $stmt = $db->prepare("SELECT id FROM cuisine_types WHERE name = ?");
                    $stmt->execute([$cuisine_type]);
                    $cuisineType = $stmt->fetch();
                    if ($cuisineType) {
                        $cuisineTypeId = $cuisineType['id'];
                    }
                } catch (Exception $e) {
                    // Handle error silently
                }
            }
            
            // Create recipe data
            $recipeData = [
                'title' => $title,
                'description' => $description,
                'instructions' => $instructions,
                'cooking_time' => $cooking_time ? (int)$cooking_time : null,
                'difficulty' => $difficulty,
                'servings' => $servings ? (int)$servings : null,
                'cuisine_type_id' => $cuisineTypeId,
                'categories' => array_map('intval', $categories),
                'ingredients' => $processedIngredients,
                'user_id' => $user['id']
            ];
            
            $recipeId = createRecipe($recipeData);
            
            if ($recipeId) {
                $_SESSION['toast_message'] = 'Recipe created successfully!';
                $_SESSION['toast_type'] = 'success';
                redirect('index.php?page=recipe-detail&id=' . $recipeId);
            } else {
                $error = 'Failed to create recipe. Please try again.';
            }
        }
    }
}

// Get categories and cuisine types for form
$categories = getCategories();
$cuisineTypes = getCuisineTypes();
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Create New Recipe</h1>
            
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900">Basic Information</h2>
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Recipe Title *
                        </label>
                        <input type="text" id="title" name="title" required 
                               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Describe your recipe..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="cooking_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Cooking Time (minutes)
                            </label>
                            <input type="number" id="cooking_time" name="cooking_time" min="1"
                                   value="<?php echo htmlspecialchars($_POST['cooking_time'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                                Difficulty Level
                            </label>
                            <select id="difficulty" name="difficulty"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="Easy" <?php echo ($_POST['difficulty'] ?? '') == 'Easy' ? 'selected' : ''; ?>>Easy</option>
                                <option value="Medium" <?php echo ($_POST['difficulty'] ?? 'Medium') == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                <option value="Hard" <?php echo ($_POST['difficulty'] ?? '') == 'Hard' ? 'selected' : ''; ?>>Hard</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="servings" class="block text-sm font-medium text-gray-700 mb-2">
                                Servings
                            </label>
                            <input type="number" id="servings" name="servings" min="1"
                                   value="<?php echo htmlspecialchars($_POST['servings'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                
                <!-- Categories and Cuisine -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900">Categories & Cuisine</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <?php foreach ($categories as $category): ?>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>"
                                       <?php echo in_array($category['id'], $_POST['categories'] ?? []) ? 'checked' : ''; ?>
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="text-sm text-gray-700"><?php echo htmlspecialchars($category['name']); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div>
                        <label for="cuisine_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Cuisine Type
                        </label>
                        <select id="cuisine_type" name="cuisine_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Cuisine Type</option>
                            <?php foreach ($cuisineTypes as $cuisineType): ?>
                            <option value="<?php echo htmlspecialchars($cuisineType['name']); ?>"
                                    <?php echo ($_POST['cuisine_type'] ?? '') == $cuisineType['name'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cuisineType['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Ingredients -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900">Ingredients *</h2>
                    
                    <div id="ingredients-container">
                        <?php 
                        $ingredients = $_POST['ingredients'] ?? [['name' => '', 'quantity' => '', 'unit' => '']];
                        foreach ($ingredients as $index => $ingredient): 
                        ?>
                        <div class="ingredient-row flex gap-4 items-end mb-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient Name</label>
                                <input type="text" name="ingredients[<?php echo $index; ?>][name]" 
                                       value="<?php echo htmlspecialchars($ingredient['name']); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="e.g., Chicken breast">
                            </div>
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="text" name="ingredients[<?php echo $index; ?>][quantity]" 
                                       value="<?php echo htmlspecialchars($ingredient['quantity']); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="1">
                            </div>
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                <input type="text" name="ingredients[<?php echo $index; ?>][unit]" 
                                       value="<?php echo htmlspecialchars($ingredient['unit']); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="lb">
                            </div>
                            <button type="button" onclick="removeIngredient(this)" 
                                    class="px-3 py-2 text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" onclick="addIngredient()" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Add Ingredient
                    </button>
                </div>
                
                <!-- Instructions -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900">Instructions *</h2>
                    
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Step-by-step Instructions
                        </label>
                        <textarea id="instructions" name="instructions" rows="8" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Write clear, step-by-step instructions for cooking..."><?php echo htmlspecialchars($_POST['instructions'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="index.php?page=recipes" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Create Recipe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let ingredientIndex = <?php echo count($_POST['ingredients'] ?? [['name' => '', 'quantity' => '', 'unit' => '']]); ?>;

function addIngredient() {
    const container = document.getElementById('ingredients-container');
    const newRow = document.createElement('div');
    newRow.className = 'ingredient-row flex gap-4 items-end mb-4';
    newRow.innerHTML = `
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient Name</label>
            <input type="text" name="ingredients[${ingredientIndex}][name]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                   placeholder="e.g., Chicken breast">
        </div>
        <div class="w-24">
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
            <input type="text" name="ingredients[${ingredientIndex}][quantity]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                   placeholder="1">
        </div>
        <div class="w-24">
            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
            <input type="text" name="ingredients[${ingredientIndex}][unit]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                   placeholder="lb">
        </div>
        <button type="button" onclick="removeIngredient(this)" 
                class="px-3 py-2 text-red-600 hover:text-red-800">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newRow);
    ingredientIndex++;
}

function removeIngredient(button) {
    const row = button.closest('.ingredient-row');
    const container = document.getElementById('ingredients-container');
    
    // Don't remove if it's the only ingredient
    if (container.children.length > 1) {
        row.remove();
    }
}
</script>

<?php include 'includes/footer.php'; ?>
