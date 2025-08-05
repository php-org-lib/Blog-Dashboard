<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
include("includes/head.php");
$message = "";
$success = "";
$errors = [];
$users = new Users($pdo);
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $bio = trim($_POST['bio']);
    $role = 'User';
    $date_created = date('Y-m-d H:i:s');

    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
        error_log("Passwords do not match");
        die("Passwords do not match");
    }
    if($users->getUserByEmail($email)) {
        $errors[] = "Email already exists";
        error_log("Email already exists");
        die("Email already exists");
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $avatar_filename = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatar_filename = uniqid("{$username}_") . '.' . $ext;
        $upload_path = __DIR__ . '/assets/img/uploads/users/' . $avatar_filename;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path)) {
            die("Error uploading avatar.");
        }
    }
    $success = $users->registerUser(
            $full_name,
            $username,
            $email,
            $hashed_password,
            $role,
            $bio,
            $avatar_filename,
            $date_created
    );
    if($success) {
        $success = "User created successfully";
        redirect('login.php');
    } else {
        $errors[] = "Failed to create user";
        error_log("Failed to create user");
        die("Failed to create user");
    }
}
?>
<section id="section">
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
            <div class="card custom-bg-dark shadow-xl border border-info">
                <div class="card-body p-3">
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
                    <form action="" class="form" method="POST" enctype="multipart/form-data" onsubmit="return preparePost();" id="register-form">
                        <div class="row m-2 p-1">
                            <div class="col-12 col-md-6">
                                <h2 class="text-gradient text-primary text-ubuntu-bold">Register</h2>
                                <h4 class="text-gradient text-info text-ubuntu-condensed-bold">Upload Your Image</h4>
                                <div class="form-group">
                                    <input type="file" id="avatar" name="avatar" accept="image/*" onchange="updateFileName()">
                                    <label for="avatar" class="custom-file-upload">Choose File</label>
                                    <span id="file-name" class="text-ubuntu-condensed-bold text-gradient text-warning">No file chosen</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group preview" id="avatarPreview">
                                    <span class="text-gradient text-success text-ubuntu-condensed-bold">No image selected</span>
                                </div>
                            </div>
                        </div>
                        <div class="row m-2 p-1">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="full_name" class="text-gradient text-info">Full Name</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control border border-info color-background custom-bg-dark text-white text-ubuntu-bold">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="email" class="text-gradient text-info">Email</label>
                                    <input type="email" name="email" id="full_name" class="form-control border border-info color-background custom-bg-dark text-white text-ubuntu-bold">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="username" class="text-gradient text-info">Username</label>
                                    <input type="text" name="username" id="username" class="form-control border border-info color-background custom-bg-dark text-white text-ubuntu-bold">
                                </div>
                            </div>
                        </div>
                        <div class="row m-2 p-1">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="hidden-content"
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

                                    <div id="register-editor"
                                         class="custom-bg-dark text-white" style="font-family: 'Ubuntu', sans-serif; font-weight: bold; font-size: 16px;"></div>

                                    <input type="hidden" name="bio" id="hidden-content">
                                </div>
                            </div>
                        </div>
                        <div class="row m-2 p-1">
                            <div class="col-12 col-md-5">
                                <div class="form-group input-group">
                                    <label for="password" class="text-gradient text-info text-ubuntu-bold">Password</label>
                                    <input type="password" name="password" id="password" class="form-control border border-info color-background custom-bg-dark text-white text-ubuntu-bold" placeholder="Enter your password...">
                                    <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group input-group">
                                    <label for="checkmark" class="text-gradient text-warning text-ubuntu-condensed-bold mx-4">Match</label>
                                    <i class="fa-solid fa-check checkmark" id="checkmark" style="font-size: 1.35rem; position: absolute; top: 105% !important; right: 50%;"></i>
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="form-group input-group">
                                    <label for="confirm_password" class="text-gradient text-info text-ubuntu-bold">Confirm Password &nbsp; &nbsp;</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control border border-info color-background custom-bg-dark text-white text-ubuntu-bold" placeholder="Confirm your password...">
                                    <i class="fa-solid fa-eye toggle-password" data-target="confirm_password"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 m-2 mb-3 p-1 px-2">
                            <input type="submit" value="Register" class="btn custom-bg-primary w-100 d-block text-white text-ubuntu-bold">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<?php include("includes/foot.php"); ?>

<script type="text/javascript">
    const imageInput = document.getElementById('avatar');
    const imagePreview = document.getElementById('avatarPreview');
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.addEventListener("load", function () {
                imagePreview.innerHTML = `<img src="${this.result}" alt="Selected Image">`;
            });
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = "<span>No image selected</span>";
        }
    });
</script>
<script type="text/javascript">
    function updateFileName() {
        const fileInput = document.getElementById("avatar");
        const fileName = document.getElementById("file-name");

        if (fileInput.files.length > 0) {
            fileName.textContent = fileInput.files[0].name;
        } else {
            fileName.textContent = "No file chosen";
        }
    }
</script>
    <script>
        let quill = new Quill('#register-editor', {
            modules: {
                syntax: true,
                toolbar: '#toolbar-container',
            },
            theme: 'snow',
    });
        function preparePost() {
        const contentInput = document.getElementById('hidden-content');
        contentInput.value = quill.root.innerHTML;
        return true;
    }
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                this.classList.add('text-success');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                    this.classList.remove('text-success');
                    this.classList.add('text-danger');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                    this.classList.remove('text-danger');
                    this.classList.add('text-success');
                }
            });
        });
    })
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const password = document.getElementById('password');
        const confirm = document.getElementById('confirm_password');
        const checkmark = document.getElementById('checkmark');

        function checkPasswordsMatch() {
            if (password.value && confirm.value && password.value === confirm.value) {
                checkmark.style.display = 'block';
            } else {
                checkmark.style.display = 'none';
            }
        }

        password.addEventListener('input', checkPasswordsMatch);
        confirm.addEventListener('input', checkPasswordsMatch);
    });
</script>