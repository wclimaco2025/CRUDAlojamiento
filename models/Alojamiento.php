<?php

class Alojamiento {
    private $conn;
    private $table = 'alojamientos';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all alojamientos from database
     * 
     * @return array|false Returns array of alojamientos on success, false on failure
     */
    public function getAll() {
        try {
            $query = "SELECT id, nombre, descripcion, ubicacion, precio, imagen_url, created_at 
                     FROM " . $this->table . " 
                     ORDER BY created_at DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all alojamientos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get alojamiento by ID
     * 
     * @param int $id
     * @return array|false Returns alojamiento data on success, false if not found
     */
    public function getById($id) {
        try {
            $query = "SELECT id, nombre, descripcion, ubicacion, precio, imagen_url, created_at 
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
            error_log("Error getting alojamiento by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new alojamiento with validation
     * 
     * @param string $nombre
     * @param string $descripcion
     * @param string $ubicacion
     * @param float $precio
     * @param string $imagen_url (optional)
     * @return bool|int Returns alojamiento ID on success, false on failure
     */
    public function create($nombre, $descripcion, $ubicacion, $precio, $imagen_url = null) {
        try {
            // Validate required fields
            if (empty($nombre) || empty($descripcion) || empty($ubicacion) || empty($precio)) {
                error_log("Error creating alojamiento: Missing required fields");
                return false;
            }
            
            // Validate precio is greater than 0
            if ($precio <= 0) {
                error_log("Error creating alojamiento: Price must be greater than 0");
                return false;
            }
            
            $query = "INSERT INTO " . $this->table . " 
                     (nombre, descripcion, ubicacion, precio, imagen_url) 
                     VALUES (:nombre, :descripcion, :ubicacion, :precio, :imagen_url)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':ubicacion', $ubicacion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':imagen_url', $imagen_url);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error creating alojamiento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing alojamiento
     * 
     * @param int $id
     * @param array $data
     * @return bool Returns true on success, false on failure
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            // Build dynamic update query based on provided data
            if (isset($data['nombre'])) {
                $fields[] = "nombre = :nombre";
                $params[':nombre'] = $data['nombre'];
            }
            if (isset($data['descripcion'])) {
                $fields[] = "descripcion = :descripcion";
                $params[':descripcion'] = $data['descripcion'];
            }
            if (isset($data['ubicacion'])) {
                $fields[] = "ubicacion = :ubicacion";
                $params[':ubicacion'] = $data['ubicacion'];
            }
            if (isset($data['precio'])) {
                // Validate precio
                if ($data['precio'] <= 0) {
                    error_log("Error updating alojamiento: Price must be greater than 0");
                    return false;
                }
                $fields[] = "precio = :precio";
                $params[':precio'] = $data['precio'];
            }
            if (isset($data['imagen_url'])) {
                $fields[] = "imagen_url = :imagen_url";
                $params[':imagen_url'] = $data['imagen_url'];
            }
            
            if (empty($fields)) {
                error_log("Error updating alojamiento: No fields to update");
                return false;
            }
            
            $query = "UPDATE " . $this->table . " 
                     SET " . implode(', ', $fields) . " 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating alojamiento: " . $e->getMessage());
            return false;
        }
    }
}
