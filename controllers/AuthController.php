<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/Session.php';
require_once __DIR__ . '/../utils/Validator.php';

class AuthController {
    private $db;
    private $userModel;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }
    
    /**
     * Handles user registration
     * Validates data, checks for duplicates, and creates new user
     */
    public function register() {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs to prevent XSS
            $username = Validator::sanitizeInput($_POST['username'] ?? '');
            $email = Validator::sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = 'regular'; // Default role
            
            // Validate required fields
            if (empty($username)) {
                $errors['username'] = 'El nombre de usuario es requerido';
            }
            
            if (empty($email)) {
                $errors['email'] = 'El correo electrónico es requerido';
            } elseif (!Validator::validateEmail($email)) {
                $errors['email'] = 'El formato del correo electrónico no es válido';
            }
            
            if (empty($password)) {
                $errors['password'] = 'La contraseña es requerida';
            } elseif (!Validator::validatePassword($password)) {
                $errors['password'] = 'La contraseña debe tener al menos 8 caracteres';
            }
            
            // Check for duplicates if no validation errors
            if (empty($errors)) {
                if ($this->userModel->findByUsername($username)) {
                    $errors['username'] = 'El nombre de usuario ya está en uso';
                }
                
                if ($this->userModel->findByEmail($email)) {
                    $errors['email'] = 'El correo electrónico ya está en uso';
                }
            }
            
            // Create user if no errors
            if (empty($errors)) {
                try {
                    $userId = $this->userModel->create($username, $email, $password, $role);
                    
                    if ($userId) {
                        // Auto-login after successful registration
                        Session::start();
                        Session::set('user_id', $userId);
                        Session::set('username', $username);
                        Session::set('role', $role);
                        
                        // Redirect to user dashboard
                        header('Location: index.php?action=dashboard');
                        exit();
                    } else {
                        $errors['general'] = 'Error al crear la cuenta. Por favor, intente nuevamente.';
                    }
                } catch (Exception $e) {
                    error_log('Registration error: ' . $e->getMessage());
                    $errors['general'] = 'Error al crear la cuenta. Por favor, intente nuevamente.';
                }
            }
        }
        
        // Show registration form with errors if any
        require_once __DIR__ . '/../views/register.php';
    }

    /**
     * Handles user login
     * Authenticates user and creates session
     */
    public function login() {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs to prevent XSS
            $usernameOrEmail = Validator::sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validate required fields
            if (empty($usernameOrEmail)) {
                $errors['username'] = 'El nombre de usuario o correo electrónico es requerido';
            }
            
            if (empty($password)) {
                $errors['password'] = 'La contraseña es requerida';
            }
            
            // Authenticate user if no validation errors
            if (empty($errors)) {
                try {
                    $user = $this->userModel->authenticate($usernameOrEmail, $password);
                    
                    if ($user) {
                        // Create session
                        Session::start();
                        Session::set('user_id', $user['id']);
                        Session::set('username', $user['username']);
                        Session::set('role', $user['role']);
                        
                        // Redirect based on role
                        header('Location: index.php?action=dashboard');
                        exit();
                    } else {
                        // Generic error message to not reveal which field is incorrect
                        $errors['general'] = 'Credenciales inválidas. Por favor, verifique su usuario y contraseña.';
                    }
                } catch (Exception $e) {
                    error_log('Login error: ' . $e->getMessage());
                    $errors['general'] = 'Error al iniciar sesión. Por favor, intente nuevamente.';
                }
            }
        }
        
        // Show login form with errors if any
        require_once __DIR__ . '/../views/login.php';
    }
    
    /**
     * Handles user logout
     * Destroys the session
     */
    public function logout() {
        Session::start();
        Session::destroy();
        
        // Redirect to landing page
        header('Location: index.php');
        exit();
    }
    
    /**
     * Checks if user is authenticated
     * Redirects to login page if not authenticated
     * 
     * @param bool $redirect Whether to redirect if not authenticated
     * @return bool True if authenticated, false otherwise
     */
    public function checkAuth($redirect = true) {
        Session::start();
        
        if (!Session::isAuthenticated()) {
            if ($redirect) {
                header('Location: index.php?action=login');
                exit();
            }
            return false;
        }
        
        return true;
    }
    
    /**
     * Checks if user has admin role
     * 
     * @return bool True if user is admin, false otherwise
     */
    public function isAdmin() {
        Session::start();
        return Session::getUserRole() === 'admin';
    }
    
    /**
     * Checks if user has required role
     * Redirects to appropriate page if not authorized
     * 
     * @param string $requiredRole The required role
     * @param bool $redirect Whether to redirect if not authorized
     * @return bool True if authorized, false otherwise
     */
    public function checkRole($requiredRole, $redirect = true) {
        $this->checkAuth($redirect);
        
        Session::start();
        $userRole = Session::getUserRole();
        
        if ($userRole !== $requiredRole) {
            if ($redirect) {
                header('Location: index.php?action=dashboard');
                exit();
            }
            return false;
        }
        
        return true;
    }
}
