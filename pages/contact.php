<?php
$pageTitle = 'Contact - FoodFusion';
include 'includes/header.php';
?>

<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-100 to-teal-100 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Get in 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-green-600">
                    Touch
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Have questions, feedback, or just want to say hello? We'd love to hear from you!
            </p>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="firstName" name="firstName" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="lastName" name="lastName" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="subject" name="subject" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="feedback">Feedback</option>
                                <option value="partnership">Partnership</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                    <div class="space-y-8">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-envelope text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Email Us</h3>
                                <p class="text-gray-600 mb-2">We'll get back to you within 24 hours</p>
                                <a href="mailto:hello@foodfusion.com" class="text-green-600 hover:text-green-700">
                                    hello@foodfusion.com
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Call Us</h3>
                                <p class="text-gray-600 mb-2">Monday - Friday, 9 AM - 6 PM EST</p>
                                <a href="tel:+1234567890" class="text-green-600 hover:text-green-700">
                                    +1 (234) 567-890
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Visit Us</h3>
                                <p class="text-gray-600 mb-2">Our headquarters</p>
                                <address class="text-green-600 not-italic">
                                    123 Culinary Street<br>
                                    Food City, FC 12345<br>
                                    United States
                                </address>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-clock text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Business Hours</h3>
                                <div class="text-gray-600 space-y-1">
                                    <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                                    <p>Saturday: 10:00 AM - 4:00 PM</p>
                                    <p>Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-green-100 transition-colors">
                                <i class="fab fa-facebook-f text-gray-600"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-green-100 transition-colors">
                                <i class="fab fa-twitter text-gray-600"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-green-100 transition-colors">
                                <i class="fab fa-instagram text-gray-600"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-green-100 transition-colors">
                                <i class="fab fa-youtube text-gray-600"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-gray-600">Find answers to common questions</p>
            </div>
            
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-gray-900">How do I create an account?</h3>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">
                            Creating an account is easy! Click the "Register" button in the top navigation, 
                            fill out the registration form with your details, and you'll be ready to start 
                            sharing recipes and connecting with our community.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-gray-900">How do I share a recipe?</h3>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">
                            Once you're logged in, click on "Recipes" in the navigation, then "Create Recipe". 
                            Fill out the recipe form with all the details including ingredients, instructions, 
                            cooking time, and upload a photo. Your recipe will be published and visible to the community.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-gray-900">Is FoodFusion free to use?</h3>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">
                            Yes! FoodFusion is completely free to use. You can browse recipes, create your own, 
                            save favorites, and participate in our community without any cost. We believe good 
                            food knowledge should be accessible to everyone.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-gray-900">How do I report inappropriate content?</h3>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">
                            If you come across content that violates our community guidelines, you can report it 
                            by clicking the "Report" button on the recipe or comment. Our moderation team will 
                            review the report and take appropriate action.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-gray-900">Can I edit or delete my recipes?</h3>
                        <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                    </button>
                    <div class="px-6 pb-4 hidden">
                        <p class="text-gray-600">
                            Absolutely! You can edit or delete your own recipes at any time. Go to your profile, 
                            click on "My Recipes", and you'll see options to edit or delete each recipe you've created.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Still Have Questions?</h2>
            <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                Don't hesitate to reach out! We're here to help and love hearing from our community.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:hello@foodfusion.com" class="bg-white hover:bg-gray-100 text-green-600 px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center">
                    <i class="fas fa-envelope mr-2"></i>
                    Email Us
                </a>
                <a href="index.php?page=register" class="bg-green-700 hover:bg-green-800 text-white px-8 py-4 rounded-lg text-lg font-medium inline-flex items-center justify-center border-2 border-white">
                    <i class="fas fa-user-plus mr-2"></i>
                    Join Community
                </a>
            </div>
        </div>
    </section>
</div>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
