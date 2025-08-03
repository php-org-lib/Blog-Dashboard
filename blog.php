<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
include('includes/head.php');
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
    echo "You are not logged in. Please login to view this page.";
}
$users = new Users($pdo);
$posts = new Posts($pdo);
$user_id = $_SESSION['user_id'];
$post_list = $posts->getPosts();
$post_list_limit_three = $posts->getThreeRecentPosts();
$current_user_posts = $users->getPostsByUserId($user_id);
$user_list = $users->getUsers();
$post_count = count($post_list);
?>
    <section id="section">
        <div class="container-fluid py-3">
            <div class="row mt-3 mb-3">
                <div class="card shadow-xl shadow-primary border border-info custom-bg-dark position-relative">
                    <div class="card-header custom-bg-dark"
                         style="display: flex; justify-content: space-between; align-items: center">
                        <h2 class="gradient-text-primary text-dm-sans-bold">Blog</h2>
                        <div class="btn-group">
                            <a href="create_post.php" class="btn custom-bg-primary-light text-ubuntu-bold text-white">Add
                                A Post</a>
                        </div>
                    </div>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-ubuntu-condensed-bold gradient-text-info">Recent Posts</h4>
                                        <?php if (!empty($post_list_limit_three)) { ?>
                                            <?php foreach ($post_list_limit_three as $recent_post) { ?>
                                                <div class="shadow-lg shadow-info shadow border border-1 border-secondary p-2 mt-2 mb-2 position-relative">
                                                    <h3 class="text-ubuntu-bold gradient-text-success text-md"><?= htmlspecialchars($recent_post['title'] ?? ''); ?></h3>
                                                    <p class="text-ubuntu-condensed-regular text-white ql-viewer text-xs"><?= nl2br(truncate_words($recent_post['content'], 10) ?? ''); ?></p>
                                                </div>
                                                <div class="mt-1 mb-1">
                                                    <hr class="horizontal info-horizontal">
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="shadow-lg shadow-info shadow border border-1 border-secondary p-2 mt-2 mb-2 position-relative">
                                                <p class="text-sm gradient-text-warning text-ubuntu-condensed-regular text-xs">
                                                    No posts yet.</p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <?php if (count($post_list) > 0) { ?>
                                    <?php foreach ($post_list as $post) { ?>
                                        <div class="shadow-lg shadow-info shadow border border-1 border-secondary p-2 mt-2 mb-2 position-relative">
                                            <h3 class="text-ubuntu-bold gradient-text-success text-lg"><?= htmlspecialchars($post['title'] ?? ''); ?></h3>
                                            <p class="text-ubuntu-condensed-regular text-white ql-viewer text-md"><?= nl2br(truncate_words($post['content'], 20) ?? ''); ?></p>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p class="text-white text-ubuntu-condensed-regular text-xs">Sorry, there are no
                                        posts made yet.</p>
                                <?php } ?>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-ubuntu-condensed-bold gradient-text-info text-md">App Users</h4>
                                        <?php if (!empty($user_list)) { ?>
                                            <table class="table table-borderless table table-responsive table-hover table-striped">
                                                <tbody>
                                                <?php foreach ($user_list as $user) { ?>

                                                    <tr>
                                                        <td class="p-1 mt-1">
                                                            <div class="d-flex px-2 py-1">
                                                                <div>
                                                                    <img src="./assets/img/uploads/users/<?= htmlspecialchars($user['avatar'] ?? ''); ?>"
                                                                         class="avatar avatar-sm me-3"
                                                                         alt="<?= htmlspecialchars($user['username'] ?? ''); ?>'s Avatar">
                                                                </div>
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-xs text-white text-dm-sans-bold"><?= htmlspecialchars($user['full_name'] ?? ''); ?></h6>
                                                                    <p class="text-xs text-white mb-0 text-dm-sans-bold"><?= htmlspecialchars($user['email'] ?? ''); ?></p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                            <p class="gradient-text-warning text-ubuntu-condensed-regular">No Users</p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include('includes/foot.php'); ?>