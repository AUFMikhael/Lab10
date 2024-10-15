<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;

class RegistrationController extends BaseController
{
    public function showForm()
    {
        // Display the registration form
        $this->render('registration_form');
    }

    public function register()
    {
        // Form input data
        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? ''
        ];

        // Validate input
        $errors = $this->validateRegistration($data);

        if (!empty($errors)) {
            // If there are errors, show the form again with error messages
            $this->render('registration_form', ['errors' => $errors, 'data' => $data]);
            return;
        }

        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Create the user model and save it
        $user = new User();
        $user->register([
            'username' => $data['username'],
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password_hash' => $hashedPassword
        ]);

        // Display success message
        $this->render('registration_success');
    }

    private function validateRegistration($data)
    {
        $errors = [];

        // Check required fields
        if (empty($data['username'])) $errors[] = "Username is required";
        if (empty($data['email'])) $errors[] = "Email is required";
        if (empty($data['password'])) $errors[] = "Password is required";
        if (empty($data['password_confirmation'])) $errors[] = "Password confirmation is required";

        // Check password rules
        if (strlen($data['password']) < 8) $errors[] = "Password must be at least 8 characters long";
        if (!preg_match('/[0-9]/', $data['password'])) $errors[] = "Password must contain at least one numeric character";
        if (!preg_match('/[a-zA-Z]/', $data['password'])) $errors[] = "Password must contain at least one non-numeric character";
        if (!preg_match('/[\W_]/', $data['password'])) $errors[] = "Password must contain at least one special character (!@#$%^&*-+)";
        if ($data['password'] !== $data['password_confirmation']) $errors[] = "Passwords do not match";

        return $errors;
    }
}