<?php

namespace App\Models;

use App\Models\BaseModel;

class User extends BaseModel
{
    public $id;
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $password_hash;
    public $created_at;
    public $updated_at;

    public function register($data)
    {
        $this->fill($data);

        // Prepare the SQL statement
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, first_name, last_name, password_hash)
            VALUES (:username, :email, :first_name, :last_name, :password_hash)
        ");

        // Bind the parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':password_hash', $this->password_hash);

        // Execute the query
        return $stmt->execute();
    }
}