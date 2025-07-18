<?php
if (!isset($active)) { $active = ''; }
$version = trim(file_get_contents(__DIR__.'/../VERSION'));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Content App Library</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Montserrat Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="inc/css/style.css?v=<?php echo $version; ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="/assets/images/mediahub-admin-logo.png" alt="MediaHub Admin" class="navbar-logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link<?php if($active==='dashboard') echo ' active'; ?>" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link<?php if($active==='stores') echo ' active'; ?>" href="stores.php">Stores</a></li>
                <li class="nav-item"><a class="nav-link<?php if($active==='uploads') echo ' active'; ?>" href="uploads.php">Content Review</a></li>
                <li class="nav-item"><a class="nav-link<?php if($active==='messages') echo ' active'; ?>" href="messages.php">Broadcasts</a></li>
                <li class="nav-item"><a class="nav-link<?php if($active==='chat') echo ' active'; ?>" href="chat.php">Chat</a></li>
                <li class="nav-item"><a class="nav-link<?php if($active==='settings') echo ' active'; ?>" href="settings.php">Settings</a></li>
                <li class="nav-item"><a class="nav-link<?php if($active==='users') echo ' active'; ?>" href="users.php">Users</a></li>
            </ul>
            <div id="adminUserInfo" class="ms-auto text-end small text-white d-flex align-items-center">
                <span class="me-2">Logged in as: <?php echo htmlspecialchars(trim(($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? ''))); ?></span>
                <span id="notifyWrap" class="position-relative me-2">
                    <i class="bi bi-bell" id="notifyBell"></i>
                    <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger" id="notifyCount">0</span>
                </span>
                <a href="logout.php" class="text-white text-decoration-none"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container-fluid pb-5">
