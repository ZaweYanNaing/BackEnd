<?php
// Sign-up popup modal component
?>

<!-- Sign-up Popup Modal -->
<div id="signupModal" class="fixed inset-0 bg-gray-200/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900">Join FoodFusion</h3>
                <button onclick="closeSignupModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <!-- Social Media Login -->
                
                
                <!-- Sign-up Form -->
                <form id="signupForm" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="popupFirstName" class="block text-sm font-medium text-gray-700 mb-1">
                                First Name
                            </label>
                            <input type="text" id="popupFirstName" name="firstName" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="popupLastName" class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name
                            </label>
                            <input type="text" id="popupLastName" name="lastName" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="popupEmail" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input type="email" id="popupEmail" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="popupPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input type="password" id="popupPassword" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="popupConfirmPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password
                        </label>
                        <input type="password" id="popupConfirmPassword" name="confirmPassword" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <!-- Terms and Privacy -->
                    <div class="flex items-start">
                        <input type="checkbox" id="popupTerms" name="terms" required
                               class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="popupTerms" class="ml-2 text-sm text-gray-600">
                            I agree to the <a href="index.php?page=privacy" class="text-green-600 hover:text-green-700 underline">Privacy Policy</a> 
                            and <a href="index.php?page=terms" class="text-green-600 hover:text-green-700 underline">Terms of Service</a>
                        </label>
                    </div>
                    
                    <!-- Error/Success Messages -->
                    <div id="popupMessage" class="hidden"></div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Create Account
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <button onclick="showLoginModal()" class="text-green-600 hover:text-green-700 font-medium">
                            Sign in
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal (for switching between signup and login) -->
<div id="loginModal" class="fixed inset-0 bg-gray-200/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900">Welcome Back</h3>
                <button onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
               
                
                <!-- Login Form -->
                <form id="loginForm" class="space-y-4">
                    <div>
                        <label for="popupLoginEmail" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input type="email" id="popupLoginEmail" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="popupLoginPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input type="password" id="popupLoginPassword" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="popupRemember" name="remember"
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="popupRemember" class="ml-2 text-sm text-gray-600">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="text-sm text-green-600 hover:text-green-700">
                            Forgot password?
                        </a>
                    </div>
                    
                    <!-- Error/Success Messages -->
                    <div id="loginMessage" class="hidden"></div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Sign In
                    </button>
                </form>
                
                <!-- Signup Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <button onclick="showSignupModal()" class="text-green-600 hover:text-green-700 font-medium">
                            Sign up
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Modal functions
function showSignupModal() {
    document.getElementById('signupModal').classList.remove('hidden');
    document.getElementById('loginModal').classList.add('hidden');
}

function closeSignupModal() {
    document.getElementById('signupModal').classList.add('hidden');
}

function showLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
    document.getElementById('signupModal').classList.add('hidden');
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const signupModal = document.getElementById('signupModal');
    const loginModal = document.getElementById('loginModal');
    
    if (event.target === signupModal) {
        closeSignupModal();
    }
    if (event.target === loginModal) {
        closeLoginModal();
    }
});

// Handle signup form submission
document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('popupMessage');
    
    // Basic validation
    if (formData.get('password') !== formData.get('confirmPassword')) {
        showMessage(messageDiv, 'Passwords do not match', 'error');
        return;
    }
    
    // Submit to register endpoint
    fetch('pages/register_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text().then(text => {
            console.log('Response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('JSON parse error:', e);
                return { success: false, error: 'Invalid response from server' };
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data);
        if (data.success) {
            showMessage(messageDiv, 'Account created successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showMessage(messageDiv, data.error || 'Registration failed', 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showMessage(messageDiv, 'An error occurred. Please try again.', 'error');
    });
});

// Handle login form submission
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageDiv = document.getElementById('loginMessage');
    
    // Submit to login endpoint
    fetch('pages/login_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Login response status:', response.status);
        return response.text().then(text => {
            console.log('Login response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Login JSON parse error:', e);
                return { success: false, error: 'Invalid response from server' };
            }
        });
    })
    .then(data => {
        console.log('Login parsed data:', data);
        if (data.success) {
            showMessage(messageDiv, 'Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showMessage(messageDiv, data.error || 'Login failed', 'error');
        }
    })
    .catch(error => {
        console.error('Login fetch error:', error);
        showMessage(messageDiv, 'An error occurred. Please try again.', 'error');
    });
});

// Show message helper function
function showMessage(element, message, type) {
    element.textContent = message;
    element.className = `text-sm p-3 rounded-md ${type === 'error' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'}`;
    element.classList.remove('hidden');
    
    // Hide message after 5 seconds
    setTimeout(() => {
        element.classList.add('hidden');
    }, 5000);
}
</script>
