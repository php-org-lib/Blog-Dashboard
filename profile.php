<?php
global $pdo;
require('includes/config/db_config.php');
require('includes/config/functions.php');
require('classes/users.php');
include('includes/head.php');
$users = new Users($pdo);
$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$userId) {
    redirect('index.php');
} else {
    $us = $users->getUserById($userId);
    $posts = $users->getPostsByUserId($userId);
}
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username'];
$email = $_SESSION['email'];
?>
<section id="section">
    <div class="container-fluid py-1">
        <div class="row mt-3 mb-3">
            <div class="col-12 col-md-7">
                <div class="card custom-bg-dark shadow-xl shadow-info border border-info position-relative">
                    <div class="card-body p-3">
                        <img src="./assets/img/uploads/users/<?= htmlspecialchars($us['avatar'] ?? ''); ?>"
                             alt="<?= htmlspecialchars($us['username'] ?? ''); ?>'s Avatar"
                             class="rounded img-fluid" width="100%" height="auto">
                        <div class="mt-1 mb-2">
                            <h2 class="text-dm-sans-bold gradient-text-info"><?= htmlspecialchars($us['full_name'] ?? '') ?></h2>
                        </div>
                        <div class="mt-1 mb-2">
                            <div class="p-1">
                                <h3 class="text-ubuntu-condensed-bold gradient-text-info">Bio</h3>
                                <p class="text-ubuntu-condensed-regular text-white"><?= htmlspecialchars(strip_tags($us['bio'] ?? '')); ?></p>
                            </div>
                        </div>
                        <div class="mt-1 mb-2">
                            <div class="p-1">
                                <h3 class="text-ubuntu-condensed-bold gradient-text-info">Other Info</h3>
                                <p class="lead text-ubuntu-condensed-bold text-white"><?= htmlspecialchars($us['username'] ?? ''); ?></p>
                                <p class="lead text-ubuntu-condensed-bold text-white"><?= htmlspecialchars($us['email'] ?? ''); ?></p>
                                <p class="lead text-ubuntu-condensed-bold text-white"><?= htmlspecialchars($us['role'] ?? ''); ?></p>
                                <p class="lead text-ubuntu-condensed-bold text-white"><?= htmlspecialchars($us['dob'] ?? date('m-d-Y')); ?></p>
                                <p class="lead text-ubuntu-condensed-bold text-white"><?= htmlspecialchars($us['date_created'] ?? ''); ?></p>
                                <p class="lead text-ubuntu-condensed-bold text-white"><?= htmlspecialchars($us['date_updated'] ?? date('m-d-Y')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-5">
                <div class="card custom-bg-dark shadow-xl shadow-info border border-info position-relative">
                    <div class="card-body p-3">

                        <div class="mt-1 mb-2">
                            <div class="p-1">
                                <h3 class="text-ubuntu-condensed-bold gradient-text-info">Posts</h3>
                                <?php if (count($posts) > 0) { ?>
                                    <?php foreach ($posts as $post) { ?>
                                        <div class="p-1 mt-1">
                                            <div style="display: flex; justify-content: space-between; align-items: center">
                                                <h6 class="text-ubuntu-condensed-bold gradient-text-success"><?= htmlspecialchars($post['title']); ?></h6>
                                                <div class="btn-group-sm">
                                                    <a href="post.php?id=<?= htmlspecialchars($post['post_id']); ?>"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-original-title="Preview post with the id:<?= htmlspecialchars($post['post_id']); ?>"
                                                       class="btn btn-sm circle-12-rounded bg-gradient-info">
                                                        <i class="fa-solid fa-list text-white"></i>
                                                    </a>
                                                    <a href="update_user.php?id=<?= htmlspecialchars($post['post_id']); ?>"
                                                       class="btn btn-sm circle-12-rounded bg-gradient-warning"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-original-title="Edit post with the id: <?= htmlspecialchars($post['post_id']); ?>">
                                                        <i class="fa-solid fa-pen-to-square text-white"></i>
                                                    </a>
                                                    <a href="index.php"
                                                       class="btn btn-sm circle-12-rounded bg-gradient-danger"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-original-title="Delete post with the id: <?= htmlspecialchars($post['post_id']); ?>">
                                                        <i class="fa-solid fa-trash text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <hr class="horizontal custom-bg-warning">
                                            <p class="text-ubuntu-condensed-regular text-white"><?= htmlspecialchars($post['content']); ?></p>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div>
                                        <p class="text-ubuntu-condensed-regular gradient-text-danger">Sorry there are no
                                            posts made yet.</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include('includes/foot.php'); ?>

