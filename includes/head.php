<?php
require_once(__DIR__ . "/../config/db_config.php");
global $pdo;
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['username'];
    $email = $_SESSION['email'];
    $the_user = new Users($pdo);
    $us = $the_user->getUserById($user_id);
} else {
    $message = 'You are not logged in';
}
$notificationCount = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM friend_requests WHERE receiver_id = ? AND status = 'pending'");
    $stmt->execute([$_SESSION['user_id']]);
    $notificationCount = $stmt->fetchColumn();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon"  href="../assets/img/uploads/site/favicon.ico">
    <title>
        Blog
    </title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="../assets/css/material-dashboard.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.core.css" />
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Ubuntu+Condensed&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
</head>

<body class="g-sidenav-show custom-bg-dark">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start custom-bg-dark ms-2 my-2" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-gradient text-info opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard-pro/pages/dashboards/analytics.html " target="_blank">
            <img src="../assets/img/uploads/site/logo.png" class="navbar-brand-img" style="width: 45px; height: auto;" alt="main_logo">
            <span class="ms-1 text-sm text-gradient text-info">Force Tech</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <?php if(isset($_SESSION['user_id'])) { ?>
            <li class="nav-item mb-2 mt-0">
                <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link gradient-text-info text-dm-sans-bold" aria-controls="ProfileNav" role="button" aria-expanded="false">
                    <img src="../assets/img/uploads/users/<?= htmlspecialchars($us['avatar'] ?? ''); ?>" class="avatar" alt="<?= htmlspecialchars($us['username'] ?? ''); ?>'s Avatar">
                    <span class="nav-link-text ms-2 ps-1"><?= htmlspecialchars($us['email'] ?? ''); ?></span>
                </a>

                <div class="collapse" id="ProfileNav" style="">
                    <ul class="nav ">
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../profile.php?id=<?= htmlspecialchars($us['id']); ?>">
                                <i class="fa-solid fa-address-card gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../user_list.php">
                                <i class="fa-solid fa-users gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> User List </span>
                            </a>
                        </li>
                        <hr class="horizontal collapse-horizontal mt-1 mb-1">
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../blog.php">
                                <i class="fa-solid fa-blog gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Blog </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../user_posts.php?id=<?= htmlspecialchars($us['id']); ?>">
                                <i class="fa-solid fa-file-lines gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Your Posts </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../create_post.php">
                                <i class="fa-solid fa-plus gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Add Post </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php } ?>
            <li class="nav-item mb-2 mt-0">
                <a data-bs-toggle="collapse" href="#PagesNav" class="nav-link gradient-text-info text-dm-sans-bold" aria-controls="PagesNav" role="button" aria-expanded="false">
                    <i class="fa-solid fa-list gradient-text-primary-secondary"></i>
                    <span class="sidenav-normal  ms-3  ps-1 text-dm-sans-bold">Pages</span>
                </a>
                <div class="collapse" id="PagesNav" style="">
                    <ul class="nav ">
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../index.php">
                                <i class="fa-solid fa-house-chimney gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Home </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../about.php">
                                <i class="fa-solid fa-circle-info gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> About </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../faq.php">
                                <i class="fa-solid fa-circle-question gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> FAQ </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../privacy_policy.php">
                                <i class="fa-solid fa-shield-halved gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Privacy Policy </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../terms_and_conditions.php">
                                <i class="fa-solid fa-scale-balanced gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Terms and Conditions </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-white" href="../contact.php">
                                <i class="fa-solid fa-address-book gradient-text-primary-secondary"></i>
                                <span class="sidenav-normal  ms-3  ps-1"> Contact </span>
                            </a>
                        </li>
                    </ul>
                </div>
            <li class="nav-item mb-2 mt-0">
                <a data-bs-toggle="collapse" href="#AccountNav" class="nav-link gradient-text-info text-dm-sans-bold" aria-controls="AccountNav" role="button" aria-expanded="false">
                    <i class="fa-solid fa-list gradient-text-primary-secondary"></i>
                    <span class="sidenav-normal  ms-3  ps-1">Account</span>
                </a>
                <div class="collapse" id="AccountNav" style="">
                    <ul class="nav ">
                        <?php if(!isset($_SESSION['user_id'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-success" href="../register.php">
                                <span class="sidenav-mini-icon gradient-text-info"> MP </span>
                                <span class="sidenav-normal  ms-3  ps-1"> Register </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-success" href="../login.php">
                                <span class="sidenav-mini-icon gradient-text-info"> S </span>
                                <span class="sidenav-normal  ms-3  ps-1"> Login </span>
                            </a>
                        </li>
                        <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link gradient-text-warning" href="../logout.php">
                                <span class="sidenav-mini-icon gradient-text-info"> L </span>
                                <span class="sidenav-normal  ms-3  ps-1"> Logout </span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-12 copyright-div">
            <div class="copyright text-center text-sm text-white text-dm-sans-bold">
                &copy; &nbsp; <?= date('Y'); ?>
            </div>
        </div>
    </div>
</aside>
<main class="main-content position-relative h-100 border-radius-lg ">
<nav class="navbar navbar-main navbar-expand-lg position-sticky mt-2 top-1 px-0 py-1 mx-3 shadow-none border-radius-lg border-info z-index-sticky custom-bg-dark navbar-dark" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-2">
            <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
                <a href="javascript:;" class="nav-link text-body p-0">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line gradient-text-primary"></i>
                        <i class="sidenav-toggler-line gradient-text-primary"></i>
                        <i class="sidenav-toggler-line gradient-text-primary"></i>
                    </div>
                </a>
            </div>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <form action="" method="GET">
                    <div class="form-group mt-0 mb-3">
                        <label for="site-search"></label>
                        <input type="text" class="form-control custom-bg-dark border border-info" id="site-search" placeholder="Search site here...">
                    </div>
                    </form>
                </div>
                <ul class="navbar-nav  justify-content-end">
                    <li class="nav-item">
                        <a href="" class="px-1 py-0 nav-link line-height-0" target="_blank">
                            <i class="material-symbols-rounded">
                                account_circle
                            </i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../friend_list.php" class="nav-link py-0 px-1 line-height-0" data-bs-original-title="Friend List" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Friend List">
                            <i class="fa-solid fa-users gradient-text-success"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown py-0 pe-3 position-relative">
                        <a class="nav-link py-0 px-1 position-relative line-height-0 text-white" id="notificationsBtn" href="../notifications.php">
                            <i class="fa-solid fa-bell gradient-text-info"></i>
                            <?php if ($notificationCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-gradient-warning">
                                    <span class="text-white text-dm-sans-bold"><?= $notificationCount ?></span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>