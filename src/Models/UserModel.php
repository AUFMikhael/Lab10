<?php
namespace App\Models;

use PDO;

class UserModel {
    // Assuming you have a database connection established
    public static function getUserByUsername($username) {
        global $conn; // Access the global database connection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllUsers() {
        global $conn; // Access the global database connection
        $stmt = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS complete_name, email FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
