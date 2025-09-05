<?php
$pageTitle = 'Educational Resources - FoodFusion';
include 'includes/header.php';
?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-100 to-teal-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Educational 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-green-600">
                    Resources
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Expand your culinary knowledge with our comprehensive collection of educational materials, 
                guides, and learning resources
            </p>
        </div>
    </section>

    <!-- Resource Categories -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Learning Categories</h2>
                <p class="text-lg text-gray-600">Choose your learning path</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Basic Cooking -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-48 bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                        <i class="fas fa-utensils text-6xl text-green-600"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Basic Cooking Techniques</h3>
                        <p class="text-gray-600 mb-4">
                            Master the fundamentals of cooking with step-by-step guides and video tutorials.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Knife skills and safety</li>
                            <li>• Heat control and timing</li>
                            <li>• Basic cooking methods</li>
                            <li>• Food safety guidelines</li>
                        </ul>
                        <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-4">
                            Start Learning <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Advanced Techniques -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <i class="fas fa-fire text-6xl text-blue-600"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Advanced Techniques</h3>
                        <p class="text-gray-600 mb-4">
                            Take your cooking to the next level with professional techniques and methods.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Sous vide cooking</li>
                            <li>• Fermentation basics</li>
                            <li>• Advanced knife work</li>
                            <li>• Plating and presentation</li>
                        </ul>
                        <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-4">
                            Explore Advanced <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Cuisine Studies -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-48 bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                        <i class="fas fa-globe text-6xl text-orange-600"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">World Cuisines</h3>
                        <p class="text-gray-600 mb-4">
                            Explore the rich traditions and techniques of cuisines from around the world.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Italian pasta making</li>
                            <li>• Asian stir-fry techniques</li>
                            <li>• French sauce making</li>
                            <li>• Mexican flavor profiles</li>
                        </ul>
                        <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-4">
                            Discover Cuisines <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Baking & Pastry -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-48 bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                        <i class="fas fa-birthday-cake text-6xl text-purple-600"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Baking & Pastry</h3>
                        <p class="text-gray-600 mb-4">
                            Learn the science and art of baking with detailed guides and techniques.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Bread making fundamentals</li>
                            <li>• Pastry techniques</li>
                            <li>• Cake decorating</li>
                            <li>• Chocolate work</li>
                        </ul>
                        <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-4">
                            Start Baking <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Nutrition & Health -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-48 bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center">
                        <i class="fas fa-heart text-6xl text-teal-600"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Nutrition & Health</h3>
                        <p class="text-gray-600 mb-4">
                            Understand the nutritional aspects of cooking and healthy eating habits.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Macronutrient basics</li>
                            <li>• Healthy cooking methods</li>
                            <li>• Dietary restrictions</li>
                            <li>• Meal planning</li>
                        </ul>
                        <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-4">
                            Learn Nutrition <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Food Science -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-48 bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center">
                        <i class="fas fa-flask text-6xl text-indigo-600"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Food Science</h3>
                        <p class="text-gray-600 mb-4">
                            Dive deep into the science behind cooking and food preparation.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• Maillard reaction</li>
                            <li>• Emulsification</li>
                            <li>• Temperature effects</li>
                            <li>• Molecular gastronomy</li>
                        </ul>
                        <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-4">
                            Explore Science <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Resources -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Resources</h2>
                <p class="text-lg text-gray-600">Handpicked educational content from our community</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=200&fit=crop" 
                         alt="Resource" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Video</span>
                            <span class="text-sm text-gray-500 ml-auto">15 min</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Knife Skills Masterclass</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Learn essential knife techniques that will transform your cooking efficiency and safety.
                        </p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm">
                            Watch Now <i class="fas fa-play ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=400&h=200&fit=crop" 
                         alt="Resource" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Guide</span>
                            <span class="text-sm text-gray-500 ml-auto">10 min read</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Understanding Heat Control</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Master the art of controlling heat in your cooking for perfect results every time.
                        </p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm">
                            Read Guide <i class="fas fa-book-open ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=200&fit=crop" 
                         alt="Resource" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Course</span>
                            <span class="text-sm text-gray-500 ml-auto">2 hours</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Bread Making Fundamentals</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Complete course covering everything from basic bread to advanced sourdough techniques.
                        </p>
                        <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm">
                            Start Course <i class="fas fa-graduation-cap ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Paths -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Structured Learning Paths</h2>
                <p class="text-lg text-gray-600">Follow our curated learning journeys</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-gray-50 rounded-lg p-8">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Beginner's Journey</h3>
                    <p class="text-gray-600 mb-6">
                        Perfect for those just starting their culinary adventure. Learn the basics step by step.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-green-600">1</span>
                            </div>
                            <span class="text-gray-700">Kitchen Setup & Safety</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-green-600">2</span>
                            </div>
                            <span class="text-gray-700">Basic Knife Skills</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-green-600">3</span>
                            </div>
                            <span class="text-gray-700">Essential Cooking Methods</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-green-600">4</span>
                            </div>
                            <span class="text-gray-700">Your First Recipes</span>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-6">
                        Start Beginner Path <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <div class="bg-gray-50 rounded-lg p-8">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Advanced Techniques</h3>
                    <p class="text-gray-600 mb-6">
                        For experienced cooks looking to refine their skills and learn professional techniques.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-blue-600">1</span>
                            </div>
                            <span class="text-gray-700">Advanced Knife Work</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-blue-600">2</span>
                            </div>
                            <span class="text-gray-700">Sauce Making Mastery</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-blue-600">3</span>
                            </div>
                            <span class="text-gray-700">Plating & Presentation</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-blue-600">4</span>
                            </div>
                            <span class="text-gray-700">Menu Development</span>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium mt-6">
                        Start Advanced Path <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Start Your Learning Journey</h2>
            <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                Join thousands of learners who are already expanding their culinary knowledge with our educational resources.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php?page=register" class="bg-white hover:bg-gray-100 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Join FoodFusion
                </a>
                <a href="index.php?page=cooking-tips" class="bg-green-700 hover:bg-green-800 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-white">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Browse Tips
                </a>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
