<?php
$pageTitle = 'About - FoodFusion';
include 'includes/header.php';
?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-100 to-teal-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                About
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-green-600">
                    FoodFusion
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Discover our story, mission, and the passionate community behind FoodFusion
            </p>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Our Story</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        FoodFusion was born from a simple belief: cooking is more than just preparing food—it's an art,
                        a science, and a way to bring people together. Founded by passionate home cooks and culinary
                        enthusiasts, we created this platform to democratize access to great recipes and cooking knowledge.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        What started as a small community of friends sharing recipes has grown into a vibrant ecosystem
                        where thousands of home cooks, professional chefs, and food lovers come together to learn,
                        share, and inspire each other.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-[#78C841]/20 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🍳</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Founded in 2024</h3>
                            <p class="text-gray-600">By passionate food enthusiasts</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 rounded-lg p-8">
                    <img src="src/images/story.jpg"
                        alt="Our Story" class="w-full h-64 object-cover rounded-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Mission & Values</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    We're committed to making cooking accessible, enjoyable, and educational for everyone
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-16 h-16 bg-[#78C841]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Education First</h3>
                    <p class="text-gray-600">
                        We believe in empowering people with knowledge, not just recipes. Every piece of content
                        is designed to teach and inspire.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-16 h-16 bg-[#B4E50D]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🤝</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Community Driven</h3>
                    <p class="text-gray-600">
                        Our platform thrives on the contributions of our community. Every recipe, tip, and review
                        makes us stronger together.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-16 h-16 bg-[#FF9B2F]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🌟</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Quality Focused</h3>
                    <p class="text-gray-600">
                        We maintain high standards for all content while remaining accessible to cooks of all skill levels.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    The passionate people behind FoodFusion
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <img src="src/images/CEO.jpg"
                        alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sarah Johnson</h3>
                    <p class="text-green-600 font-medium mb-2">Founder & CEO</p>
                    <p class="text-gray-600">
                        Professional chef with 15 years of experience. Passionate about making cooking accessible to everyone.
                    </p>
                </div>

                <div class="text-center">
                    <img src="src/images/Head.jpg"
                        alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Maria Rodriguez</h3>
                    <p class="text-green-600 font-medium mb-2">Head of Community</p>
                    <p class="text-gray-600">
                        Food blogger and community manager. Loves connecting people through shared culinary experiences.
                    </p>
                </div>

                <div class="text-center">
                    <img src="src/images/Lead.jpg"
                        alt="Team Member" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">David Chen</h3>
                    <p class="text-green-600 font-medium mb-2">Technical Lead</p>
                    <p class="text-gray-600">
                        Developer and food enthusiast. Ensures our platform runs smoothly so you can focus on cooking.
                    </p>
                </div>
            </div>
        </div>
    </section>

 
    

    <!-- CTA Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Join Our Community</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-3xl mx-auto">
                Ready to start your culinary journey? Join thousands of food enthusiasts who are already sharing,
                learning, and growing together.
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
                <a href="index.php?page=recipes" class="bg-white hover:bg-gray-50 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-green-600">
                    <i class="fas fa-book-open mr-2"></i>
                    Explore Recipes
                </a>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>