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
        include 'pages/login.php';
        break;
    case 'register':
        include 'pages/register.php';
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
    case 'cooking-tips':
        include 'pages/cooking-tips.php';
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
    case '404':
        include 'pages/404.php';
        break;
    default:
        include 'pages/404.php';
        break;
}
?>
