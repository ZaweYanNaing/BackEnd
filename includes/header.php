<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'FoodFusion'; ?></title>
    <link href="src/output.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #78C841 0%, #B4E50D 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #78C841 0%, #B4E50D 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            transition: transform 0.3s ease;
            z-index: 50;
        }
        
        .sidebar.collapsed {
            width: 4rem;
        }
        
        .sidebar-content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .sidebar-main {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }
        
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .sidebar-group {
            margin-bottom: 1rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu-item {
            margin: 0;
        }
        
        .sidebar-menu-button {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #64748b;
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
        }
        
        .sidebar-menu-button:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        
        .sidebar-menu-button.active {
            background: #dcfce7;
            color: #16a34a;
            border-right: 2px solid #16a34a;
        }
        
        .sidebar-menu-button i {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .sidebar.collapsed .sidebar-menu-button span {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-menu-button i {
            margin-right: 0;
        }
        
        .sidebar.collapsed .sidebar-menu-button {
            justify-content: center;
        }
        
        .sidebar.collapsed .user-dropdown .sidebar-menu-button {
            flex-direction: column;
            align-items: center;
            padding: 0.75rem 0.5rem;
            justify-content: center;
            pointer-events: none;
            cursor: default;
        }
        
        .sidebar.collapsed .user-dropdown .sidebar-menu-button img {
            width: 2.5rem;
            height: 2.5rem;
            margin-left: 1rem;
            border-radius: 0.75rem;
            object-fit: cover;
            display: block;
            background-color: #78C841;
            border: 2px solid #e2e8f0;
            flex-shrink: 0;
            max-width: 2.5rem;
            max-height: 2.5rem;
            min-width: 2.5rem;
            min-height: 2.5rem;
        }
        
        .sidebar.collapsed .user-dropdown {
            pointer-events: none;
            width: 100%;
            display: flex;
            justify-content: center;
        }
        
        .sidebar.collapsed .user-dropdown .sidebar-menu-button .flex-1 {
            display: none !important;
            visibility: hidden !important;
        }
        
        .sidebar.collapsed .user-dropdown .sidebar-menu-button i {
            display: none !important;
            visibility: hidden !important;
        }
        
        .sidebar.collapsed .user-dropdown .sidebar-menu-button .font-medium,
        .sidebar.collapsed .user-dropdown .sidebar-menu-button .text-xs {
            display: none !important;
            visibility: hidden !important;
        }
        
        .sidebar.collapsed .user-dropdown .sidebar-menu-button {
            width: 100%;
            height: auto;
            padding: 1rem 0.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 4rem;
        }
        
        .sidebar.collapsed .sidebar-header .flex-1 {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-header .text-sm {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 1rem 0.5rem;
        }
        
        .sidebar.collapsed .sidebar-header .flex {
            justify-content: center;
        }
        
        .sidebar.collapsed .sidebar-header .w-8 {
            width: 2rem;
            height: 2rem;
        }
        
        .sidebar.collapsed .sidebar-header .w-8 i {
            font-size: 1rem;
            color: white !important;
            display: block;
        }
        
        .sidebar.collapsed .sidebar-header .w-8 {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar.collapsed .user-dropdown {
            position: relative;
        }
        
        
        .sidebar.collapsed .sidebar-header:hover::after {
            content: "FoodFusion Community";
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1000;
            margin-left: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.collapsed .sidebar-header {
            position: relative;
        }
        
        .main-content {
            margin-left: 16rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .main-content.sidebar-collapsed {
            margin-left: 4rem;
        }
        
        .main-header {
            height: 4rem;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }
        
        .sidebar-trigger {
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            color: #64748b;
            border-radius: 0.375rem;
        }
        
        .sidebar-trigger:hover {
            background: #f1f5f9;
        }
        
        .user-dropdown {
            position: relative;
        }
        
        .user-dropdown-content {
            position: absolute;
            right: 0;
            bottom: 100%;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            min-width: 12rem;
            z-index: 50;
            display: none;
            margin-bottom: 0.5rem;
        }
        
        .user-dropdown.open .user-dropdown-content {
            display: block;
        }
        
        .user-dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #374151;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .user-dropdown-item:hover {
            background: #f9fafb;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                display: none;
            }
            
            .mobile-overlay.open {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="mobile-overlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-content">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="flex items-center">
                    <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-utensils text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">FoodFusion</div>
                        <div class="text-xs text-gray-500">Community</div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Main Content -->
            <div class="sidebar-main">
                <div class="sidebar-group">
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="index.php" class="sidebar-menu-button <?php echo ($page == 'home') ? 'active' : ''; ?>">
                                <i class="fas fa-home"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="index.php?page=about" class="sidebar-menu-button <?php echo ($page == 'about') ? 'active' : ''; ?>">
                                <i class="fas fa-info-circle"></i>
                                <span>About</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="index.php?page=recipes" class="sidebar-menu-button <?php echo ($page == 'recipes') ? 'active' : ''; ?>">
                                <i class="fas fa-book-open"></i>
                                <span>Recipes</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="index.php?page=community" class="sidebar-menu-button <?php echo ($page == 'community') ? 'active' : ''; ?>">
                                <i class="fas fa-users"></i>
                                <span>Community</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="index.php?page=culinary" class="sidebar-menu-button <?php echo ($page == 'culinary') ? 'active' : ''; ?>">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Culinary Resources</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="index.php?page=resources" class="sidebar-menu-button <?php echo ($page == 'resources') ? 'active' : ''; ?>">
                                <i class="fas fa-file-text"></i>
                                <span>Educational Resources</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="index.php?page=contact" class="sidebar-menu-button <?php echo ($page == 'contact') ? 'active' : ''; ?>">
                                <i class="fas fa-phone"></i>
                                <span>Contact</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <?php if (!$isLoggedIn): ?>
                <div class="sidebar-group">
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <button onclick="showLoginModal()" class="sidebar-menu-button w-full text-left">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Sign In</span>
                            </button>
                        </li>
                        <li class="sidebar-menu-item">
                            <button onclick="showSignupModal()" class="sidebar-menu-button w-full text-left">
                                <i class="fas fa-user-plus"></i>
                                <span>Join Us</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar Footer -->
            <?php if ($isLoggedIn): ?>
            <div class="sidebar-footer">
                <div class="user-dropdown" id="user-dropdown">
                    <button class="sidebar-menu-button w-full" onclick="toggleUserDropdown()">
                        <?php if ($user['profile_image']): ?>
                            <img src="uploads/<?php echo $user['profile_image']; ?>" 
                                 alt="Profile" class="w-8 h-8 rounded-lg mr-3 object-cover">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-lg mr-3 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                <i class="fas fa-user text-white text-xs ml-5 mt-1"></i>
                            </div>
                        <?php endif; ?>
                        <div class="flex-1 text-left">
                            <div class="font-medium text-sm"><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></div>
                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                        <i class="fas fa-chevron-up text-xs"></i>
                    </button>
                    
                    <div class="user-dropdown-content">
                        <a href="index.php?page=profile" class="user-dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="index.php?page=recipes&user_id=<?php echo $user['id']; ?>" class="user-dropdown-item">
                            <i class="fas fa-book mr-2"></i> My Recipes
                        </a>
                        <a href="index.php?page=favorites" class="user-dropdown-item">
                            <i class="fas fa-heart mr-2"></i> Favorites
                        </a>
                        <hr class="my-1">
                        <a href="index.php?page=logout" class="user-dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Log out
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </aside>
    
    <!-- Sign-up Popup and Cookie Banner -->
    <?php include 'includes/signup-popup.php'; ?>
    <?php include 'includes/cookie-banner.php'; ?>
    
    <!-- Main Content -->
    <div id="main-content" class="main-content">
        <!-- Main Header -->
        <header class="main-header">
            <div class="flex items-center gap-2">
                <button class="sidebar-trigger" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="h-4 w-px bg-gray-300 mr-2"></div>
                <div class="text-sm font-medium text-gray-900">FoodFusion</div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="flex-1 p-6">
