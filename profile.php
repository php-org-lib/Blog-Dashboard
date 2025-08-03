<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
include("includes/head.php");
$errors = [];
$success = '';
$message = '';
$users = new Users($pdo);
$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
    $message = "You must be logged in to view this page.";
    echo $message;
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username'];
$email = $_SESSION['email'];
if (!$userId) {
    redirect('index.php');
    $errors[] = 'Sorry, there was an error. Please try again.';
} else {
    $us = $users->getUserById($userId);
    $posts = $users->getPostsByUserId($userId);
    if (empty($posts)) {
        $posts = [];
        $message = 'Sorry, there are no posts made yet.';
    }
    if (empty($us)) {
        $us = null;
        $errors[] = 'Sorry, there was an error. Could not find user with the id: ' . $userId . '.';
        redirect('login.php');
    }
}

?>
<section id="section">
    <div class="container-fluid py-1">
        <div class="row-mt-1 mb-1">
            <div class="col-12 col-md-10 offset-md-1">
                <?php if (!empty($errors)) { ?>
                    <div class="alert custom-bg-danger mt-2 mb-3 p-2">
                        <?php foreach ($errors as $error) { ?>
                            <p class="gradient-text-white text-ubuntu-bold"><?php echo $error; ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if (!empty($success)) { ?>
                    <div class="alert custom-bg-success mt-2 mb-3 p-2">
                        <p class="gradient-text-white text-ubuntu-bold"><?php echo $success; ?></p>
                    </div>
                <?php } ?>
                <?php if (!empty($message)) { ?>
                    <div class="alert custom-bg-info mt-2 mb-3 p-2">
                        <p class="gradient-text-white text-ubuntu-bold"><?php echo $message; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-12 col-md-7">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <select class="form-select form-select-lg mb-3 custom-bg-dark bg-dark text-white text-ubuntu-condensed-bold text-md" id="postSelect" aria-label="Select a post">
                                <?php if (!empty($posts)) { ?>
                                    <?php foreach ($posts as $index => $post) { ?>
                                        <option value="pills-<?= $post['post_id']; ?>" <?= $index === 0 ? 'selected' : ''; ?>
                                                class="gradient-text-primary-light text-ubuntu-condensed-bold text-md">
                                            <?= $post['post_id']; ?>: <?= htmlspecialchars(truncate_words($post['title'], 6)); ?>
                                        </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="tab-content" id="pills-tabContent">
                            <?php if (!empty($posts)) {
                                foreach ($posts as $index => $post) { ?>
                                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : ''; ?>"
                                         id="pills-<?= $post['post_id']; ?>" role="tabpanel"
                                         aria-labelledby="pills-<?= $post['post_id']; ?>-tab">
                                        <div style="display: block; justify-content: space-between; align-items: center">
                                            <h4 class="text-ubuntu-condensed-bold gradient-text-success"><?= htmlspecialchars($post['title']); ?></h4>
                                            <div class="btn-group-sm d-inline">
                                                <a href="post.php?id=<?= htmlspecialchars($post['post_id']); ?>"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="Preview post with the id:<?= htmlspecialchars($post['post_id']); ?>"
                                                   class="btn btn-sm circle-12-rounded bg-gradient-info">
                                                    <i class="fa-solid fa-list text-white text-md"></i>
                                                </a>
                                                <a href="update_user.php?id=<?= htmlspecialchars($post['post_id']); ?>"
                                                   class="btn btn-sm circle-12-rounded bg-gradient-warning"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-original-title="Edit post with the id: <?= htmlspecialchars($post['post_id']); ?>">
                                                    <i class="fa-solid fa-pen-to-square text-white text-md"></i>
                                                </a>
                                                <form action="includes/process_delete_post.php" class="d-inline" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['post_id']); ?>">
                                                    <button type="submit" class="btn btn-sm circle-12-rounded bg-gradient-danger" data-bs-toggle="tooltip"
                                                            data-bs-original-title="Delete post with the id: <?= htmlspecialchars($post['post_id']); ?>">
                                                        <i class="fa-solid fa-trash text-white text-md"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="px-3 py-2 ql-viewer text-white">
                                            <?= nl2br($post['content']); ?>
                                        </div>
                                    </div>
                                <?php }} ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-5">
                <div class="card custom-bg-dark shadow-xl shadow-info border border-info position-relative">
                    <div class="card-body p-3">
                        <img src="./assets/img/uploads/users/<?= htmlspecialchars($us['avatar'] ?? ''); ?>"
                             alt="<?= htmlspecialchars($us['username'] ?? ''); ?>'s Avatar"
                             class="rounded img-fluid" width="100%" height="auto">
                        <hr class="horizontal info-horizontal horizontal-line-info">
                        <div class="mt-1 mb-2">
                            <h2 class="text-dm-sans-bold gradient-text-info"><?= htmlspecialchars($us['full_name'] ?? '') ?></h2>
                        </div>
                        <div class="mt-1 mb-2">
                            <div class="p-1">
                                <h3 class="text-ubuntu-condensed-bold gradient-text-info">Bio</h3>
                                <p class="text-ubuntu-condensed-regular text-white ql-viewer"><?= nl2br($us['bio'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="mt-1 mb-2">
                            <div class="p-1">
                                <h3 class="text-ubuntu-condensed-bold gradient-text-info">Other Info</h3>
                                <p class="lead text-ubuntu-condensed-bold text-white">
                                    <span class="gradient-text-info text-dm-sans-bold">USERNAME: </span>
                                    <?= htmlspecialchars($us['username'] ?? ''); ?>
                                </p>
                                <p class="lead text-ubuntu-condensed-bold text-white">
                                    <span class="gradient-text-info text-dm-sans-bold">EMAIL: </span>
                                    <?= htmlspecialchars($us['email'] ?? ''); ?>
                                </p>
                                <p class="lead text-ubuntu-condensed-bold text-white">
                                    <span class="gradient-text-info text-dm-sans-bold">ROLE: </span>
                                    <?= htmlspecialchars($us['role'] ?? ''); ?>
                                </p>
                                <p class="lead text-ubuntu-condensed-bold text-white">
                                    <span class="gradient-text-info text-dm-sans-bold">DOB: </span>
                                    <?= htmlspecialchars($us['dob'] ?? date('m-d-Y')); ?>
                                </p>
                                <p class="lead text-ubuntu-condensed-bold text-white">
                                    <span class="gradient-text-info text-dm-sans-bold">CREATED: </span>
                                    <?= htmlspecialchars($us['date_created'] ?? ''); ?>
                                </p>
                                <p class="lead text-ubuntu-condensed-bold text-white">
                                    <span class="gradient-text-info text-dm-sans-bold">UPDATED: </span>
                                    <?= htmlspecialchars($us['date_updated'] ?? date('m-d-Y')); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    const select = document.getElementById('postSelect');
    const tabPanes = document.querySelectorAll('.tab-pane');
    function showSelectedPost(postId) {
        tabPanes.forEach(pane => pane.classList.remove('show', 'active'));
        const selectedPane = document.getElementById(postId);
        if (selectedPane) {
            selectedPane.classList.add('show', 'active');
        }
    }
    select.addEventListener('change', function () {
        showSelectedPost(this.value);
    });
    window.addEventListener('DOMContentLoaded', () => {
        showSelectedPost(select.value);
    });
</script>
<?php include('includes/foot.php'); ?>

