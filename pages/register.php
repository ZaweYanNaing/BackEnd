<?php
$pageTitle = 'Register - FoodFusion';
include 'includes/header.php';

// Redirect if already logged in
if ($isLoggedIn) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = sanitizeInput($_POST['firstName'] ?? '');
    $lastName = sanitizeInput($_POST['lastName'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $bio = sanitizeInput($_POST['bio'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');
    $website = sanitizeInput($_POST['website'] ?? '');
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        // Check if user already exists
        $existingUser = getUserByEmail($email);
        if ($existingUser) {
            $error = 'An account with this email already exists.';
        } else {
            // Create user
            $userData = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password,
                'bio' => $bio,
                'location' => $location,
                'website' => $website
            ];
            
            if (createUser($userData)) {
                $_SESSION['toast_message'] = 'Account created successfully! Please log in.';
                $_SESSION['toast_type'] = 'success';
                redirect('index.php?page=login');
            } else {
                $error = 'Failed to create account. Please try again.';
            }
        }
    }
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 gradient-bg rounded-lg flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="index.php?page=login" class="font-medium text-green-600 hover:text-green-500">
                    sign in to your existing account
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST">
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                <div class="flex">
                    <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">
                            First Name *
                        </label>
                        <input id="firstName" name="firstName" type="text" required 
                               value="<?php echo htmlspecialchars($firstName ?? ''); ?>"
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">
                            Last Name *
                        </label>
                        <input id="lastName" name="lastName" type="text" required 
                               value="<?php echo htmlspecialchars($lastName ?? ''); ?>"
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address *
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           value="<?php echo htmlspecialchars($email ?? ''); ?>"
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password *
                        </label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">
                            Confirm Password *
                        </label>
                        <input id="confirmPassword" name="confirmPassword" type="password" required 
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">
                        Bio
                    </label>
                    <textarea id="bio" name="bio" rows="3" 
                              class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                              placeholder="Tell us about yourself..."><?php echo htmlspecialchars($bio ?? ''); ?></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">
                            Location
                        </label>
                        <input id="location" name="location" type="text" 
                               value="<?php echo htmlspecialchars($location ?? ''); ?>"
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                               placeholder="City, Country">
                    </div>
                    
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">
                            Website
                        </label>
                        <input id="website" name="website" type="url" 
                               value="<?php echo htmlspecialchars($website ?? ''); ?>"
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                               placeholder="https://yourwebsite.com">
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    I agree to the 
                    <a href="#" class="text-green-600 hover:text-green-500">Terms of Service</a>
                    and
                    <a href="#" class="text-green-600 hover:text-green-500">Privacy Policy</a>
                </label>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-green-500 group-hover:text-green-400"></i>
                    </span>
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
