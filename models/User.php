<?php

class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create a new user with hashed password
     * 
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $role (default: 'regular')
     * @return bool|int Returns user ID on success, false on failure
     */
    public function create($username, $email, $password, $role = 'regular') {
        try {
            $query = "INSERT INTO " . $this->table . " 
                     (username, email, password_hash, role) 
                     VALUES (:username, :email, :password_hash, :role)";
            
            $stmt = $this->conn->prepare($query);
            
            // Hash the password using bcrypt
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':role', $role);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Authenticate user with username/email and password
     * 
     * @param string $usernameOrEmail
     * @param string $password
     * @return array|false Returns user data on success, false on failure
     */
    public function authenticate($usernameOrEmail, $password) {
        try {
            $query = "SELECT id, username, email, password_hash, role, created_at 
                     FROM " . $this->table . " 
                     WHERE username = :identifier OR email = :identifier 
                     LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':identifier', $usernameOrEmail);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify password
                if (password_verify($password, $user['password_hash'])) {
                    // Remove password hash from returned data
                    unset($user['password_hash']);
                    return $user;
                }
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error authenticating user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|false Returns user data on success, false if not found
     */
    public function findByUsername($username) {
        try {
            $query = "SELECT id, username, email, role, created_at 
                     FROM " . $this->table . " 
                     WHERE username = :username 
                     LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error finding user by username: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find user by email
     * 
     * @param string $email
     * @return array|false Returns user data on success, false if not found
     */
    public function findByEmail($email) {
        try {
            $query = "SELECT id, username, email, role, created_at 
                     FROM " . $this->table . " 
                     WHERE email = :email 
                     LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error finding user by email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find user by ID
     * 
     * @param int $id
     * @return array|false Returns user data on success, false if not found
     */
    public function findById($id) {
        try {
            $query = "SELECT id, username, email, role, created_at 
                     FROM " . $this->table . " 
                     WHERE id = :id 
                     LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return false;
        }
    }
}
