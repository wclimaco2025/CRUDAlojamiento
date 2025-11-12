<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Alojamiento.php';
require_once __DIR__ . '/../utils/Session.php';
require_once __DIR__ . '/../utils/Validator.php';

class AlojamientoController {
    private $db;
    private $alojamientoModel;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->alojamientoModel = new Alojamiento($this->db);
    }
    
    /**
     * Display all alojamientos
     * Used for landing page and general listing
     */
    public function index() {
        try {
            $alojamientos = $this->alojamientoModel->getAll();
            
            if ($alojamientos === false) {
                $alojamientos = [];
                $error = 'Error al cargar los alojamientos. Por favor, intente nuevamente.';
            }
            
            // This will be used by the landing page view
            return [
                'alojamientos' => $alojamientos,
                'error' => $error ?? null
            ];
        } catch (Exception $e) {
            error_log('Error in AlojamientoController::index: ' . $e->getMessage());
            return [
                'alojamientos' => [],
                'error' => 'Error al cargar los alojamientos. Por favor, intente nuevamente.'
            ];
        }
    }
    
    /**
     * Create a new alojamiento
     * Only accessible by administrators
     */
    public function create() {
        $errors = [];
        $success = false;
        
        // Verify user is authenticated and has admin role
        Session::start();
        if (!Session::isAuthenticated()) {
            header('Location: index.php?action=login');
            exit();
        }
        
        if (Session::getUserRole() !== 'admin') {
            header('Location: index.php?action=dashboard');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize inputs to prevent XSS
            $nombre = Validator::sanitizeInput($_POST['nombre'] ?? '');
            $descripcion = Validator::sanitizeInput($_POST['descripcion'] ?? '');
            $ubicacion = Validator::sanitizeInput($_POST['ubicacion'] ?? '');
            $precio = $_POST['precio'] ?? '';
            $imagen_url = Validator::sanitizeInput($_POST['imagen_url'] ?? '');
            
            // Validate required fields
            if (empty($nombre)) {
                $errors['nombre'] = 'El nombre es requerido';
            }
            
            if (empty($descripcion)) {
                $errors['descripcion'] = 'La descripción es requerida';
            }
            
            if (empty($ubicacion)) {
                $errors['ubicacion'] = 'La ubicación es requerida';
            }
            
            if (empty($precio)) {
                $errors['precio'] = 'El precio es requerido';
            } elseif (!is_numeric($precio) || $precio <= 0) {
                $errors['precio'] = 'El precio debe ser un número mayor a 0';
            }
            
            // Create alojamiento if no errors
            if (empty($errors)) {
                try {
                    $alojamientoId = $this->alojamientoModel->create(
                        $nombre,
                        $descripcion,
                        $ubicacion,
                        floatval($precio),
                        !empty($imagen_url) ? $imagen_url : null
                    );
                    
                    if ($alojamientoId) {
                        $success = true;
                        // Clear form data on success
                        $nombre = $descripcion = $ubicacion = $precio = $imagen_url = '';
                    } else {
                        $errors['general'] = 'Error al crear el alojamiento. Por favor, intente nuevamente.';
                    }
                } catch (Exception $e) {
                    error_log('Error creating alojamiento: ' . $e->getMessage());
                    $errors['general'] = 'Error al crear el alojamiento. Por favor, intente nuevamente.';
                }
            }
        }
        
        // Return data for the view
        return [
            'errors' => $errors,
            'success' => $success,
            'nombre' => $nombre ?? '',
            'descripcion' => $descripcion ?? '',
            'ubicacion' => $ubicacion ?? '',
            'precio' => $precio ?? '',
            'imagen_url' => $imagen_url ?? ''
        ];
    }
    
    /**
     * Show details of a specific alojamiento
     * 
     * @param int $id Alojamiento ID
     */
    public function show($id) {
        try {
            // Validate ID
            if (empty($id) || !is_numeric($id)) {
                return [
                    'alojamiento' => null,
                    'error' => 'ID de alojamiento inválido'
                ];
            }
            
            $alojamiento = $this->alojamientoModel->getById($id);
            
            if ($alojamiento === false) {
                return [
                    'alojamiento' => null,
                    'error' => 'Alojamiento no encontrado'
                ];
            }
            
            return [
                'alojamiento' => $alojamiento,
                'error' => null
            ];
        } catch (Exception $e) {
            error_log('Error in AlojamientoController::show: ' . $e->getMessage());
            return [
                'alojamiento' => null,
                'error' => 'Error al cargar el alojamiento. Por favor, intente nuevamente.'
            ];
        }
    }
}
