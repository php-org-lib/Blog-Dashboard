<?php
session_start();
require_once __DIR__ . '/config/db_config.php';
require_once __DIR__ . '/config/functions.php';
require_once __DIR__ . '/config/classes/users.php';
require_once __DIR__ . '/config/classes/posts.php';
require_once __DIR__ . '/config/classes/comments.php';
require_once __DIR__ . '/config/classes/friendships.php';
spl_autoload_register(function ($className) {
    $file = __DIR__ . '/classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});