<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Alojamiento.php';
require_once __DIR__ . '/../models/UserAlojamiento.php';
require_once __DIR__ . '/../utils/Session.php';
require_once __DIR__ . '/../utils/Validator.php';

class UserController {
    private $db;
    private $alojamientoModel;
    private $userAlojamientoModel;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->alojamientoModel = new Alojamiento($this->db);
        $this->userAlojamientoModel = new UserAlojamiento($this->db);
    }
    
    /**
     * Display user dashboard based on role
     * Shows different views for regular users and administrators
     */
    public function dashboard() {
        // Verify authentication
        Session::start();
        if (!Session::isAuthenticated()) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $userId = Session::getUserId();
        $userRole = Session::getUserRole();
        
        try {
            // Get all alojamientos
            $allAlojamientos = $this->alojamientoModel->getAll();
            
            if ($allAlojamientos === false) {
                $allAlojamientos = [];
                $error = 'Error al cargar los alojamientos. Por favor, intente nuevamente.';
            }
            
            // For regular users, get their selected alojamientos
            $selectedAlojamientos = [];
            $selectedIds = [];
            
            if ($userRole === 'regular') {
                $selectedAlojamientos = $this->userAlojamientoModel->getUserAlojamientos($userId);
                
                if ($selectedAlojamientos === false) {
                    $selectedAlojamientos = [];
                } else {
                    // Create array of selected IDs for easy checking
                    $selectedIds = array_column($selectedAlojamientos, 'id');
                }
            }
            
            // Return data for the view
            return [
                'allAlojamientos' => $allAlojamientos,
                'selectedAlojamientos' => $selectedAlojamientos,
                'selectedIds' => $selectedIds,
                'userRole' => $userRole,
                'error' => $error ?? null
            ];
        } catch (Exception $e) {
            error_log('Error in UserController::dashboard: ' . $e->getMessage());
            return [
                'allAlojamientos' => [],
                'selectedAlojamientos' => [],
                'selectedIds' => [],
                'userRole' => $userRole,
                'error' => 'Error al cargar el dashboard. Por favor, intente nuevamente.'
            ];
        }
    }
    
    /**
     * Select an alojamiento for the user
     * Verifies duplicates and creates association
     */
    public function selectAlojamiento() {
        // Verify authentication
        Session::start();
        if (!Session::isAuthenticated()) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $userId = Session::getUserId();
        $userRole = Session::getUserRole();
        
        // Only regular users can select alojamientos
        if ($userRole !== 'regular') {
            header('Location: index.php?action=dashboard');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alojamientoId = $_POST['alojamiento_id'] ?? '';
            
            // Validate alojamiento ID
            if (empty($alojamientoId) || !is_numeric($alojamientoId)) {
                $_SESSION['error'] = 'ID de alojamiento inválido';
                header('Location: index.php?action=dashboard');
                exit();
            }
            
            try {
                // Check if already selected
                if ($this->userAlojamientoModel->isAlojamientoSelected($userId, $alojamientoId)) {
                    $_SESSION['error'] = 'Este alojamiento ya está en tu cuenta';
                    header('Location: index.php?action=dashboard');
                    exit();
                }
                
                // Add alojamiento to user's selection
                $result = $this->userAlojamientoModel->addAlojamiento($userId, $alojamientoId);
                
                if ($result) {
                    $_SESSION['success'] = 'Alojamiento agregado exitosamente';
                } else {
                    $_SESSION['error'] = 'Error al agregar el alojamiento. Por favor, intente nuevamente.';
                }
            } catch (Exception $e) {
                error_log('Error in UserController::selectAlojamiento: ' . $e->getMessage());
                $_SESSION['error'] = 'Error al agregar el alojamiento. Por favor, intente nuevamente.';
            }
        }
        
        header('Location: index.php?action=dashboard');
        exit();
    }
    
    /**
     * Remove an alojamiento from user's selection
     * Deletes the association between user and alojamiento
     */
    public function removeAlojamiento() {
        // Verify authentication
        Session::start();
        if (!Session::isAuthenticated()) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $userId = Session::getUserId();
        $userRole = Session::getUserRole();
        
        // Only regular users can remove alojamientos from their selection
        if ($userRole !== 'regular') {
            header('Location: index.php?action=dashboard');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alojamientoId = $_POST['alojamiento_id'] ?? '';
            
            // Validate alojamiento ID
            if (empty($alojamientoId) || !is_numeric($alojamientoId)) {
                $_SESSION['error'] = 'ID de alojamiento inválido';
                header('Location: index.php?action=dashboard');
                exit();
            }
            
            try {
                // Remove alojamiento from user's selection
                $result = $this->userAlojamientoModel->removeAlojamiento($userId, $alojamientoId);
                
                if ($result) {
                    $_SESSION['success'] = 'Alojamiento eliminado exitosamente';
                } else {
                    $_SESSION['error'] = 'Error al eliminar el alojamiento. Por favor, intente nuevamente.';
                }
            } catch (Exception $e) {
                error_log('Error in UserController::removeAlojamiento: ' . $e->getMessage());
                $_SESSION['error'] = 'Error al eliminar el alojamiento. Por favor, intente nuevamente.';
            }
        }
        
        header('Location: index.php?action=dashboard');
        exit();
    }
}
