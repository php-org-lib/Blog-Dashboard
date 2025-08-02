<?php
global $pdo;
require("includes/config/db_config.php");
require("includes/config/functions.php");
require("classes/users.php");
include("includes/head.php");
$users = new Users($pdo);
$message = "";
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
        error_log("Passwords do not match");
        die("Passwords do not match");
    }
    if($users->getUserByEmail($email)) {
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
        $message = "User created successfully";
        echo '<script>window.location.href = "login.php";</script>';
        echo "<script>alert('$message');</script>";
        exit;
    } else {
        $message = "Failed to create user";
        error_log("Failed to create user");
        echo "<script>alert('$message');</script>";
        die("Failed to create user");
    }
}
?>
<section id="section">
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
            <div class="card custom-bg-dark shadow-xl shadow-primary border-1 border-primary">
                <div class="card-body p-3">
                    <form action="" class="form" method="POST" enctype="multipart/form-data" onsubmit="syncQuillContent()">
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
                                    <input type="text" name="full_name" id="full_name" class="form-control border border-primary color-background custom-bg-dark text-white text-ubuntu-bold">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="email" class="text-gradient text-info">Email</label>
                                    <input type="email" name="email" id="full_name" class="form-control border border-primary color-background custom-bg-dark text-white text-ubuntu-bold">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label for="username" class="text-gradient text-info">Username</label>
                                    <input type="text" name="username" id="username" class="form-control border border-primary color-background custom-bg-dark text-white text-ubuntu-bold">
                                </div>
                            </div>
                        </div>
                        <div class="row m-2 p-1">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="hidden-content" class="text-gradient text-info text-ubuntu-bold">Bio</label>
                                    <div id="toolbar" style="color: dodgerblue; font-weight: bold; font-size: 0.9rem;">
                                        <select class="ql-font"></select>
                                        <select class="ql-size"></select>
                                        <button class="ql-bold"></button>
                                        <button class="ql-italic"></button>
                                        <button class="ql-underline"></button>
                                        <button class="ql-strike"></button>
                                        <button class="ql-blockquote"></button>
                                        <button class="ql-code-block"></button>
                                        <button class="ql-link"></button>
                                        <button class="ql-image"></button>
                                        <button class="ql-video"></button>
                                        <button class="ql-clean"></button>
                                        <select class="ql-color"></select>
                                        <select class="ql-background"></select>
                                        <select class="ql-align"></select>
                                        <button class="ql-bold"></button>
                                        <button class="ql-italic"></button>
                                        <button class="ql-underline"></button>
                                        <button class="ql-list" value="ordered"></button>
                                        <button class="ql-list" value="bullet"></button>
                                    </div>
                                    <div id="register-bio" class="custom-bg-dark text-white text-ubuntu-bold"></div>
                                    <input type="hidden" name="bio" id="hidden-content">
                                </div>
                            </div>
                        </div>
                        <div class="row m-2 p-1">
                            <div class="col-12 col-md-5">
                                <div class="form-group input-group">
                                    <label for="password" class="text-gradient text-info text-ubuntu-bold">Password</label>
                                    <input type="password" name="password" id="password" class="form-control border border-primary color-background custom-bg-dark text-white text-ubuntu-bold" placeholder="Enter your password...">
                                    <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group input-group">
                                    <label for="checkmark" class="text-gradient text-warning text-ubuntu-condensed-bold mx-4">Match</label>
                                    <i class="fa-solid fa-check checkmark" id="checkmark" style="font-size: 1.35rem; position: absolute; top: 105% !important; right: 47%;"></i>
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="form-group input-group">
                                    <label for="confirm_password" class="text-gradient text-info text-ubuntu-bold">Confirm Password &nbsp; &nbsp;</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control border border-primary color-background custom-bg-dark text-white text-ubuntu-bold" placeholder="Confirm your password...">
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
    const Size = Quill.import('attributors/style/size');
    Size.whitelist = ['small', 'normal', 'large', 'huge'];
    Quill.register(Size, true);

    const Font = Quill.import('attributors/class/font');
    Font.whitelist = ['sans-serif', 'serif', 'monospace'];
    Quill.register(Font, true);

    const quill = new Quill('#register-bio', {
        theme: 'snow',
        modules: {
            toolbar: '#toolbar'
        },
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }]
        ]
    });
    function syncQuillContent() {
        const content = document.querySelector('#hidden-content');
        content.value = quill.root.innerHTML;
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