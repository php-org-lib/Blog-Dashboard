<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
include("includes/head.php");
$posts = new Posts($pdo);
$errors = [];
$success = '';
$message = '';
$user_id = $_SESSION['user_id'];
if(!isset($_SESSION['user_id'])) {
    $message = "You must be logged in to create a post.";
    redirect('login.php');
    echo $message;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    if ($user_id && $title && $content) {
        $posts->createPost($user_id, $title, $content);
        $success = "Post created successfully";
        redirect('blog.php');
    }
    if ($success) {
        redirect('blog.php');
        exit;
    }
}
?>
<section id="section">
    <div class="container-fluid py-3">
        <div class="row mt-3 mb-3">
            <div class="col-12 col-md-10 offset-md-1">
                <div class="card custom-bg-dark shadow-xl shadow-info">
                    <div class="card-header custom-bg-dark">
                        <h2 class="text-ubuntu-condensed-bold gradient-text-info">Add Post</h2>
                    </div>
                    <div class="card-body p-3">
                        <form class="form" action="" method="POST" onsubmit="return preparePost();">
                            <div class="row-mt-1 mb-1">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <?php if (!empty($errors)) { ?>
                                        <div class="alert auto-dismiss custom-bg-danger mt-2 mb-3 p-2" role="alert">
                                            <?php foreach ($errors as $error) { ?>
                                                <p class="gradient-text-white text-ubuntu-bold"><?php echo $error; ?></p>
                                                <button type="button" class="close-alert text-danger float-end" aria-label="Close">&times;</button>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($success)) { ?>
                                        <div class="alert auto-dismiss custom-bg-success mt-2 mb-3 p-2" role="alert">
                                            <p class="gradient-text-white text-ubuntu-bold"><?php echo $success; ?></p>
                                            <button type="button" class="close-alert text-danger float-end" aria-label="Close">&times;</button>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($message)) { ?>
                                        <div class="alert alert-dismiss custom-bg-info mt-2 mb-3 p-2" role="alert">
                                            <p class="gradient-text-white text-ubuntu-bold"><?php echo $message; ?></p>
                                            <button type="button" class="close-alert text-danger float-end" aria-label="Close">&times;</button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="title"
                                               class="gradient-text-info text-ubuntu-condensed-bold">Title</label>
                                        <input type="text" name="title" id="title"
                                               class="form-control color-background custom-bg-dark border border-info text-ubuntu-condensed-bold text-white"
                                               placeholder="Title" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="content"
                                               class="gradient-text-info text-ubuntu-condensed-bold">Content</label>
                                        <div id="toolbar-container">
                                          <span class="ql-formats">
                                            <select class="ql-font"></select>
                                            <select class="ql-size"></select>
                                          </span>
                                            <span class="ql-formats">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-strike"></button>
                                              </span>
                                            <span class="ql-formats">
                                            <select class="ql-color"></select>
                                            <select class="ql-background"></select>
                                          </span>
                                            <span class="ql-formats">
                                                <button class="ql-script" value="sub"></button>
                                                <button class="ql-script" value="super"></button>
                                              </span>
                                            <span class="ql-formats">
                                            <button class="ql-header" value="1"></button>
                                            <button class="ql-header" value="2"></button>
                                            <button class="ql-blockquote"></button>
                                            <button class="ql-code-block"></button>
                                          </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                                <button class="ql-indent" value="-1"></button>
                                                <button class="ql-indent" value="+1"></button>
                                              </span>
                                            <span class="ql-formats">
                                                <button class="ql-direction" value="rtl"></button>
                                                <select class="ql-align"></select>
                                              </span>
                                            <span class="ql-formats">
                                                <button class="ql-link"></button>
                                                <button class="ql-image"></button>
                                                <button class="ql-video"></button>
                                                <button class="ql-formula"></button>
                                              </span>
                                            <span class="ql-formats">
                                                <button class="ql-clean"></button>
                                            </span>
                                        </div>

                                            <div id="editor"
                                                 class="custom-bg-dark text-white text-ubuntu-bold"></div>

                                        <input type="hidden" name="content" id="content">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 px-2 mb-3">
                                <input type="submit"
                                       class="btn custom-bg-primary text-dm-sans-bold text-white d-block w-100"
                                       value="Add Post">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include("includes/foot.php"); ?>
<script>
    let quill = new Quill('#editor', {
        modules: {
            syntax: true,
            toolbar: '#toolbar-container',
        },
        theme: 'snow',
    });
    function preparePost() {
        const contentInput = document.getElementById('content');
        contentInput.value = quill.root.innerHTML;
        return true;
    }

</script>
