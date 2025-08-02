<?php
session_start();
require_once("includes/config/db_config.php");

class Users {
    private $pdo;
    private $table = "users";
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getUsers() {
        $sql = "SELECT * FROM " . $this->table;
        return pdo($this->pdo, $sql)->fetchAll();
    }
    public function getUserById(int $id) {
        $sql = "SELECT * FROM ". $this->table . " WHERE id = :id";
        return pdo($this->pdo, $sql, ["id" => $id])->fetch();
    }
    public function getPostsByUserId(int $id) {
        $sql = "SELECT p.id AS post_id, p.*, u.* FROM posts p 
                JOIN users u ON p.user_id = u.id WHERE p.user_id = ?
                ORDER BY p.date_created DESC;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserByEmail(string $email) {
        $sql = "SELECT * FROM ". $this->table . " WHERE email = :email";
        return pdo($this->pdo, $sql, ["email" => $email])->fetch();
    }
   /* public function createUser(array $data) {
        $sql = "INSERT INTO ". $this->table . " (email, password, full_name, avatar, bio, role, dob, date_created) VALUES (:email, :password, :full_name, :avatar, :bio, :role, :dob, NOW())";
        return pdo($this->pdo, $sql,
            [
                ":email" => $data['email'],
                ":password" => $data['password'],
                ':full_name' => $data['full_name'],
                ':avatar' => $data['avatar'],
                ':bio' => $data['bio'],
                ':role' => $data['role'],
                ':dob' => $data['dob'],
                ':date_created' => $data['date_created'],
            ]);
    }*/
    public function updateUser(int $id, $full_name, $avatar, $bio, $dob, $date_updated) {
        $sql = "UPDATE ". $this->table . " SET full_name = :full_name, avatar = :avatar, 
         bio = :bio, dob = :dob, date_updated = :date_updated WHERE id = :id";
        return pdo($this->pdo, $sql,
            [
                'id' => $id,
                'full_name' => $full_name,
                'avatar' => $avatar,
                'bio' => $bio,
                'dob' => $dob,
                'date_updated' => $date_updated
            ]);
    }
    public function registerUser($full_name, $username, $email, $password, $role, $bio, $avatar_filename, $date_created) {
        $sql = "INSERT INTO users (full_name, username, email, password, role, bio, avatar, date_created)
                VALUES (:full_name, :username, :email, :password, :role,  :bio, :avatar, :date_created)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':full_name'     => $full_name,
            ':username'      => $username,
            ':email'         => $email,
            ':password'      => $password,
            ':role'          => $role,
            ':bio'           => $bio,
            ':avatar'        => $avatar_filename,
            ':date_created'  => $date_created
        ]);
    }

    public function exists($field, $value) {
        $sql = "SELECT COUNT(*) FROM users WHERE $field = :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':value' => $value]);
        return $stmt->fetchColumn() > 0;
    }
    public function loginUser($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $update = $this->pdo->prepare("UPDATE users SET is_logged_in = 1 WHERE id = :id");
            $update->execute([':id' => $user['id']]);
            return $user;
        }
        return false;
    }
    public function isLoggedIn() {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }
    public function logout() {
        try {
            if (isset($_SESSION['user_id'])) {
                $sql = "UPDATE users SET is_logged_in = 0 WHERE id = ?;";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$_SESSION['user_id']]);
            }
            $_SESSION = array();
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            unset($_SESSION['user_id']);
            unset($_SESSION['username']);
            unset($_SESSION['email']);
            unset($_SESSION['full_name']);
            unset($_SESSION['avatar']);
            unset($_SESSION['is_logged_in']);
            session_destroy();
            redirect('login.php');
            $message = "You have been logged out successfully";
            echo "<script>alert('$message');</script>";
            return true;
        } catch(PDOException $e) {
            redirect('login.php');
            $message = "There has been an error logging out: " . $e->getMessage();
            echo "<script>alert('$message');</script>";
            return false;
        }
    }

    public function getProfile(int $id): array {
        $profile = [
            'user' => null,
            'posts' => []
        ];
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                return $profile;
            }
            $profile['user'] = $user;
            $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE user_id = :id ORDER BY date_created DESC");
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $profile['posts'] = $posts;
            return $profile;
        } catch (PDOException $e) {
            error_log("getProfile Error: " . $e->getMessage());
            echo "<script>alert('There has been an error getting the profile: " . $e->getMessage() . "');</script>";
            return $profile;
        }
    }
    public function deleteUser(int $userId): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM posts WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Delete User Error: " . $e->getMessage());
            return false;
        }
    }
    public static function getSessionUserId()
    {
        $user_id = $_SESSION['user_id'];
        if($user_id == null) {
            $user_id = '';
        }
        return $user_id;
    }
    public static function getSessionUserEmail()
    {
        $user_email = $_SESSION['email'];
        if($user_email == null) {
            $user_email = '';
        }
        return $user_email;
    }
    public static function getSessionUserName()
    {
        $user_name = $_SESSION['username'];
        if($user_name == null) {
            $user_name = '';
        }
        return $user_name;
    }



}