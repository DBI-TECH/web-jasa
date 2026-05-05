<?php
if(!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if(!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
    }
}

if(!function_exists('redirectIfNotLoggedIn')) {
    function redirectIfNotLoggedIn() {
        if(!isLoggedIn()) {
            header("Location: ../login.php");
            exit();
        }
    }
}

if(!function_exists('redirectIfNotAdmin')) {
    function redirectIfNotAdmin() {
        if(!isAdmin()) {
            header("Location: ../index.php");
            exit();
        }
    }
}
?>