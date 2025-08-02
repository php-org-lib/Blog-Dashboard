<?php
global $pdo;
require('includes/config/db_config.php');
require('includes/config/functions.php');
require('classes/users.php');
include('includes/head.php');
$users = new Users($pdo);
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_UNSAFE_RAW);
    $user = $users->loginUser($email, $password);
    if($user) {
        session_start();
        $message = "Login successful";
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['avatar'] = $user['avatar'];
        $_SESSION['is_logged_in'] = 1;
        redirect('blog.php');
        echo "<script>alert('$message');</script>";
    } else {
        $message = "Login failed. Invalid email or password";;
        echo "<script>alert('$message');</script>";
    }
}
?>
<section id="section">
<div class="container-fluid py-1 mt-3 mb-3">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3 col-lg-4 offset-lg-4 py-5 login-form">
            <div class="card custom-bg-dark border border-primary shadow-xl shadow-primary login-card">
               <div class="card-header custom-bg-dark" style="display: flex; justify-content: space-between; align-items: center">
                   <h1 class="text-dm-sans-bold gradient-text-info">Login</h1>
                   <div class="btn-group">
                       <a href="register.php" class="btn rounded btn-outline-success">Register</a>
                   </div>
               </div>
                <div class="card-body p-3">
                    <form action="" method="POST" class="form">
                        <div class="form-group m-2">
                            <label for="email" class="text-ubuntu-bold gradient-text-primary">Email</label>
                            <input type="email" name="email" class="form-control color-background custom-bg-dark border border-primary text-dm-sans-bold text-white" id="email" required>
                        </div>
                        <div class="form-group input-group m-2">
                            <label for="password" class="text-ubuntu-bold gradient-text-primary">Password</label>
                            <input type="password" name="password" id="password" class="form-control color-background custom-bg-dark border border-primary text-dm-sans-bold text-white" required>
                            <i class="fa-solid fa-eye toggle-password" data-target="password"></i>
                        </div>
                        <div class="py-1 mt-3 mb-3">
                            <input type="submit" value="Login" class="btn custom-bg-primary text-dm-sans-bold text-white btn-block w-100">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

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
<?php include("includes/foot.php"); ?>
