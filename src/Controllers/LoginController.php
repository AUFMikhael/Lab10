<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

class LoginController extends BaseController
{
    public function showLoginForm()
    {
        // Check if the user is already logged in, if so, redirect
        if (isset($_SESSION['is_logged_in'])) {
            header("Location: /welcome");
            exit();
        }

        // Get failed attempts, default to 0 if not set
        $failedAttempts = isset($_SESSION['failed_attempts']) ? $_SESSION['failed_attempts'] : 0;

        // Use the render method from BaseController (via Renderable trait)
        $this->render('login', ['failed_attempts' => $failedAttempts]); // No .mustache extension here
    }

    public function handleLogin()
    {
        if (isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= 3) {
            echo "Too many failed attempts. Please try again later.";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = UserModel::getUserByUsername($username);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['is_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['failed_attempts'] = 0;
                header("Location: /welcome");
                exit();
            } else {
                $_SESSION['failed_attempts'] = isset($_SESSION['failed_attempts']) ? $_SESSION['failed_attempts'] + 1 : 1;
                header("Location: /login");
                exit();
            }
        }
    }

    public function showWelcomePage()
    {
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: /login");
            exit();
        }

        $users = UserModel::getAllUsers();
        $this->render('welcome', ['users' => $users]); // No .mustache extension here
    }

    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }
}