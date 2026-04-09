<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FeedBook - A modern multi-author blogging platform. Share your stories with the world.">
    <title><?= $pageTitle ?? 'FeedBook' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/feedbook/frontend/css/style.css">
</head>
<body>
<!-- Toast container -->
<div id="toastContainer" class="toast-container"></div>

<nav class="navbar">
    <div class="container navbar-content">
        <a href="/feedbook/" class="logo">📖 FeedBook</a>

        <!-- Search bar -->
        <form class="search-bar" id="searchForm" action="javascript:void(0)" onsubmit="handleSearch(event)">
            <input type="text" id="searchInput" placeholder="Search posts..." autocomplete="off">
            <button type="submit" class="search-btn">🔍</button>
        </form>

        <button class="mobile-menu-btn" onclick="document.querySelector('.nav-menu').classList.toggle('active')">☰</button>

        <ul class="nav-menu">
            <li><a href="/feedbook/">Home</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/feedbook/dashboard">Dashboard</a></li>
                <li><a href="/feedbook/create-post">New Post</a></li>
                <li><a href="/feedbook/profile">Profile</a></li>
                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <li><a href="/feedbook/admin-users">Admin</a></li>
                <?php endif; ?>
                <li><a href="/feedbook/api/logout" class="btn btn-outline btn-small">Logout</a></li>
            <?php else: ?>
                <li><a href="/feedbook/login">Login</a></li>
                <li><a href="/feedbook/register" class="btn btn-primary btn-small">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Search results overlay -->
<div id="searchResults" class="search-results-overlay hidden"></div>

<main class="container">
