<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Simple routing
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$user = null;
if ($isLoggedIn) {
    $user = getUserById($_SESSION['user_id']);
}

// Include the appropriate page
switch ($page) {
    case 'home':
        include 'pages/home.php';
        break;
    case 'about':
        include 'pages/about.php';
        break;
    case 'login':
        // Redirect to home page since login is now handled by popup
        redirect('index.php');
        break;
    case 'register':
        // Redirect to home page since registration is now handled by popup
        redirect('index.php');
        break;
    case 'logout':
        include 'pages/logout.php';
        break;
    case 'profile':
        include 'pages/profile.php';
        break;
    case 'edit-profile':
        include 'pages/edit-profile.php';
        break;
    case 'favorites':
        include 'pages/favorites.php';
        break;
    case 'recipes':
        include 'pages/recipes.php';
        break;
    case 'recipe-detail':
        include 'pages/recipe-detail.php';
        break;
    case 'create-recipe':
        include 'pages/create-recipe.php';
        break;
    case 'edit-recipe':
        include 'pages/edit-recipe.php';
        break;
    case 'delete-recipe':
        include 'pages/delete-recipe.php';
        break;
    case 'culinary':
        include 'pages/culinary.php';
        break;
    case 'community':
        include 'pages/community.php';
        break;
    case 'resources':
        include 'pages/resources.php';
        break;
    case 'contact':
        include 'pages/contact.php';
        break;
    case 'search':
        include 'pages/search.php';
        break;
    case 'privacy':
        include 'pages/privacy.php';
        break;
    case 'terms':
        include 'pages/terms.php';
        break;
    case 'cookies':
        include 'pages/cookies.php';
        break;
    case '404':
        include 'pages/404.php';
        break;
    default:
        include 'pages/404.php';
        break;
}
?>
