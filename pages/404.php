<?php
$pageTitle = 'Page Not Found - FoodFusion';
include 'includes/header.php';
?>

<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-exclamation-triangle text-8xl text-gray-300 mb-4"></i>
            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Sorry, the page you are looking for doesn't exist or has been moved.
            </p>
        </div>
        
        <div class="space-y-4">
            <a href="index.php" 
               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                <i class="fas fa-home mr-2"></i>
                Go Home
            </a>
            
            <div class="text-sm text-gray-500">
                <a href="index.php?page=recipes" class="hover:text-green-600 mr-4">Browse Recipes</a>
                <a href="index.php?page=cooking-tips" class="hover:text-green-600 mr-4">Cooking Tips</a>
                <a href="index.php?page=search" class="hover:text-green-600">Search</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
