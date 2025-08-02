<?php
global $pdo;
require('includes/config/db_config.php');
require('includes/config/functions.php');
require('classes/users.php');
require('classes/posts.php');
include('includes/head.php');
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
    echo "You are not logged in. Please login to view this page.";
} else {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['username'];
    $email = $_SESSION['email'];
    $avatar = $_SESSION['avatar'];
    $full_name = $_SESSION['full_name'];
    $is_logged_in = $_SESSION['is_logged_in'];
}
$posts = new Posts($pdo);
$post_list = $posts->getPosts();
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
                            <a href="create_post.php" class="btn custom-bg-primary-light text-ubuntu-bold text-white">Add A Post</a>
                        </div>
                    </div>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-ubuntu-condensed-bold gradient-text-info">Recent Posts</h4>
                                        <p class="text-ubuntu-condensed-regular text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aspernatur eius in magni natus numquam perspiciatis quidem quod ut vel.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-ubuntu-condensed-bold gradient-text-info">Your Posts</h4>
                                        <p class="text-ubuntu-condensed-regular text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aspernatur eius in magni natus numquam perspiciatis quidem quod ut vel.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <?php if (count($post_list) > 0) { ?>
                                    <?php foreach ($post_list as $post) { ?>
                                        <div class="shadow-lg shadow-info shadow border border-1 border-secondary p-2 mt-2 mb-2 position-relative">
                                            <h3 class="text-ubuntu-bold gradient-text-success"><?= htmlspecialchars($post['title'] ?? ''); ?></h3>
                                            <p class="text-ubuntu-condensed-regular text-white"><?= htmlspecialchars($post['content'] ?? ''); ?></p>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p class="text-white text-ubuntu-condensed-regular">Sorry, there are no posts made yet.</p>
                                    <p class="text-ubuntu-condensed-regular text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aspernatur eius in magni natus numquam perspiciatis quidem quod ut vel.</p>
                                <?php } ?>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-ubuntu-condensed-bold gradient-text-info">Your Comments</h4>
                                        <p class="text-ubuntu-condensed-regular text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aspernatur eius in magni natus numquam perspiciatis quidem quod ut vel.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-ubuntu-condensed-bold gradient-text-info">Title Text</h4>
                                        <p class="text-ubuntu-condensed-regular text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aspernatur eius in magni natus numquam perspiciatis quidem quod ut vel.</p>
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