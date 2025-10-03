<?php
$pageTitle = 'Edit Profile - FoodFusion';
include 'includes/header.php';
require_once __DIR__ . '/../includes/functions.php';

// Require login
requireLogin();

$error = '';
$success = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = sanitizeInput($_POST['firstName'] ?? '');
    $lastName = sanitizeInput($_POST['lastName'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $bio = sanitizeInput($_POST['bio'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');
    $website = sanitizeInput($_POST['website'] ?? '');
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email)) {
        $error = 'First name, last name, and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if email is already taken by another user
        $existingUser = getUserByEmail($email);
        if ($existingUser && $existingUser['id'] != $user['id']) {
            $error = 'This email is already taken by another user.';
        } else {
            // Handle profile image upload
            $profileImage = $user['profile_image']; // Keep existing image by default
            
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_image'];
                
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $fileType = mime_content_type($file['tmp_name']);
                
                if (in_array($fileType, $allowedTypes)) {
                    // Validate file size (max 5MB)
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    if ($file['size'] <= $maxSize) {
                        // Generate unique filename
                        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $filename = 'profile_' . $user['id'] . '_' . time() . '_' . uniqid() . '.' . $extension;
                        $uploadPath = __DIR__ . '/../uploads/' . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                            // Delete old profile image if it exists
                            if ($user['profile_image'] && file_exists(__DIR__ . '/../uploads/' . $user['profile_image'])) {
                                unlink(__DIR__ . '/../uploads/' . $user['profile_image']);
                            }
                            $profileImage = $filename;
                        }
                    } else {
                        $error = 'Profile image too large. Maximum size is 5MB.';
                    }
                } else {
                    $error = 'Invalid file type for profile image. Only JPEG, PNG, GIF, and WebP images are allowed.';
                }
            }
            
            if (empty($error)) {
                // Update user
                $userData = [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email,
                    'bio' => $bio,
                    'location' => $location,
                    'website' => $website,
                    'profile_image' => $profileImage
                ];
                
                if (updateUser($user['id'], $userData)) {
                    // Update session user data
                    $user = getUserById($user['id']); // Refresh user data
                    $_SESSION['toast_message'] = 'Profile updated successfully!';
                    $_SESSION['toast_type'] = 'success';
                    redirect('index.php?page=profile');
                } else {
                    $error = 'Failed to update profile. Please try again.';
                }
            }
        }
    }
}
?>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Profile</h1>
            
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-8">
                <!-- Profile Image -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900">Profile Picture</h2>
                    
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            <img id="profile-preview" 
                                 src="<?php echo $user['profile_image'] ? 'uploads/' . $user['profile_image'] : 'https://via.placeholder.com/120x120/78C841/FFFFFF?text=' . substr($user['firstName'], 0, 1); ?>" 
                                 alt="Profile" class="w-24 h-24 rounded-full object-cover">
                        </div>
                        
                        <div>
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload New Photo
                            </label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF, WebP up to 5MB</p>
                        </div>
                    </div>
                </div>
                
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name *
                            </label>
                            <input type="text" id="firstName" name="firstName" required 
                                   value="<?php echo htmlspecialchars($user['firstName']); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name *
                            </label>
                            <input type="text" id="lastName" name="lastName" required 
                                   value="<?php echo htmlspecialchars($user['lastName']); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($user['email']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900">Additional Information</h2>
                    
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                            Bio
                        </label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio']); ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Location
                            </label>
                            <input type="text" id="location" name="location" 
                                   value="<?php echo htmlspecialchars($user['location']); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="City, Country">
                        </div>
                        
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                                Website
                            </label>
                            <input type="url" id="website" name="website" 
                                   value="<?php echo htmlspecialchars($user['website']); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="https://yourwebsite.com">
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="index.php?page=profile" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Profile image preview
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include 'includes/footer.php'; ?>
