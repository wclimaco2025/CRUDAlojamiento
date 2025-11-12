<?php
/**
 * Main entry point for the application
 * Handles routing, session initialization, and error handling
 */

// Initialize session at the start of each request
require_once __DIR__ . '/../utils/Session.php';
Session::start();

// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Include required controllers
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/AlojamientoController.php';
require_once __DIR__ . '/../controllers/UserController.php';

// Get the action from query parameter (default to 'landing')
$action = $_GET['action'] ?? 'landing';

try {
    // Route the request to appropriate controller and method
    switch ($action) {
        case 'landing':
            // Landing page - show all alojamientos
            $alojamientoController = new AlojamientoController();
            $data = $alojamientoController->index();
            extract($data);
            require_once __DIR__ . '/../views/landing.php';
            break;
            
        case 'register':
            // User registration
            $authController = new AuthController();
            $authController->register();
            break;
            
        case 'login':
            // User login
            $authController = new AuthController();
            $authController->login();
            break;
            
        case 'logout':
            // User logout
            $authController = new AuthController();
            $authController->logout();
            break;
            
        case 'dashboard':
            // User dashboard (different views for regular users and admins)
            $userController = new UserController();
            $data = $userController->dashboard();
            extract($data);
            
            // Get session messages and clear them
            $success = Session::get('success');
            $error = Session::get('error');
            if ($success) {
                Session::set('success', null);
            }
            if ($error) {
                Session::set('error', null);
            }
            
            // Load appropriate view based on user role
            if ($userRole === 'admin') {
                // For admin, rename allAlojamientos to alojamientos for the view
                $alojamientos = $allAlojamientos;
                
                require_once __DIR__ . '/../views/admin_dashboard.php';
            } else {
                require_once __DIR__ . '/../views/user_dashboard.php';
            }
            break;
            
        case 'createAlojamiento':
        case 'create_alojamiento':
            // Create new alojamiento (admin only)
            $alojamientoController = new AlojamientoController();
            $createData = $alojamientoController->create();
            
            // Get dashboard data
            $userController = new UserController();
            $data = $userController->dashboard();
            extract($data);
            
            // Rename for view compatibility
            $alojamientos = $allAlojamientos;
            
            // Extract create data (errors, success, form fields)
            $formData = [
                'nombre' => $createData['nombre'] ?? '',
                'descripcion' => $createData['descripcion'] ?? '',
                'ubicacion' => $createData['ubicacion'] ?? '',
                'precio' => $createData['precio'] ?? '',
                'imagen_url' => $createData['imagen_url'] ?? ''
            ];
            $errors = $createData['errors'] ?? [];
            $success = $createData['success'] ?? false;
            
            require_once __DIR__ . '/../views/admin_dashboard.php';
            break;
            
        case 'selectAlojamiento':
        case 'select_alojamiento':
            // Select alojamiento (regular user only)
            $userController = new UserController();
            $userController->selectAlojamiento();
            break;
            
        case 'removeAlojamiento':
        case 'remove_alojamiento':
            // Remove alojamiento from user's selection (regular user only)
            $userController = new UserController();
            $userController->removeAlojamiento();
            break;
            
        case 'show_alojamiento':
            // Show details of a specific alojamiento
            $id = $_GET['id'] ?? null;
            $alojamientoController = new AlojamientoController();
            $data = $alojamientoController->show($id);
            extract($data);
            
            // For now, redirect back to landing if alojamiento not found
            if ($alojamiento === null) {
                header('Location: index.php?action=landing');
                exit();
            }
            
            // You can create a detail view later if needed
            // For now, redirect to landing
            header('Location: index.php?action=landing');
            exit();
            break;
            
        default:
            // Unknown action - redirect to landing page
            header('Location: index.php?action=landing');
            exit();
            break;
    }
    
} catch (PDOException $e) {
    // Database errors
    error_log('Database error: ' . $e->getMessage());
    
    // Show generic error page
    $errorMessage = 'Error de base de datos. Por favor, intente nuevamente más tarde.';
    require_once __DIR__ . '/../views/error.php';
    
} catch (Exception $e) {
    // General errors
    error_log('Application error: ' . $e->getMessage());
    
    // Show generic error page
    $errorMessage = 'Ha ocurrido un error. Por favor, intente nuevamente más tarde.';
    require_once __DIR__ . '/../views/error.php';
}
