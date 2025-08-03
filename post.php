<?php
global $pdo;

require_once __DIR__ . '/autoload.php';
include("includes/head.php");
if(!isset($_SESSION['user_id'])) {
    redirect('login.php');
    echo '<script>alert("You are not logged in. Please login to view this page.");</script>';
}
$posts = new Posts($pdo);
$postId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if($postId == null) {
    redirect('index.php');
}
$post = $posts->getPostById($postId);
?>
    <section id="section">
        <div class="container-fluid py-3">
        <div class="row mt-3 mb-3">
            <div class="col-12 col-md-10 offset-md-1">
                <div class="card shadow-xl shadow-info border border-info custom-bg-dark position-relative">
                    <div class="card-header custom-bg-dark">
                        <h2 class="text-dm-sans-bold gradient-text-info"><?= htmlspecialchars($post['title'] ?? ''); ?></h2>
                    </div>
                    <div class="card-body p-3">
                        <p class="text-white text-ubuntu-condensed-regular ql-viewer"><?= nl2br($post['content'] ?? ''); ?></p>
                    </div>
                    <div class="card-footer p-1">
                        <p class="gradient-text-info text-ubuntu-condensed-bold">
                            <?= htmlspecialchars($post['date_created'] ?? ''); ?>
                            <br>
                            Post By: <?= htmlspecialchars($post['author_username'] ?? ''); ?></p>
                        <div class="d-flex px-2 py-1">
                            <div>
                                <img src="./assets/img/uploads/users/<?= htmlspecialchars($post['author_avatar'] ?? ''); ?>" class="avatar avatar-sm me-3" alt="<?= htmlspecialchars($post['author_username'] ?? ''); ?>'s Avatar">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs gradient-text-info text-dm-sans-bold"><?= htmlspecialchars($post['author_name'] ?? ''); ?></h6>
                                <p class="text-xs gradient-text-info mb-0 text-dm-sans-bold"><?= htmlspecialchars($post['author_email'] ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include("includes/foot.php"); ?>