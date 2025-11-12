<?php

class UserAlojamiento {
    private $conn;
    private $table = 'user_alojamientos';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Add alojamiento to user's selection
     * Creates association between user and alojamiento
     * 
     * @param int $user_id
     * @param int $alojamiento_id
     * @return bool|int Returns association ID on success, false on failure
     */
    public function addAlojamiento($user_id, $alojamiento_id) {
        try {
            // Check if already selected to prevent duplicates
            if ($this->isAlojamientoSelected($user_id, $alojamiento_id)) {
                error_log("Alojamiento already selected by user");
                return false;
            }

            $query = "INSERT INTO " . $this->table . " 
                     (user_id, alojamiento_id) 
                     VALUES (:user_id, :alojamiento_id)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':alojamiento_id', $alojamiento_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error adding alojamiento to user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove alojamiento from user's selection
     * Deletes association between user and alojamiento
     * 
     * @param int $user_id
     * @param int $alojamiento_id
     * @return bool Returns true on success, false on failure
     */
    public function removeAlojamiento($user_id, $alojamiento_id) {
        try {
            $query = "DELETE FROM " . $this->table . " 
                     WHERE user_id = :user_id AND alojamiento_id = :alojamiento_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':alojamiento_id', $alojamiento_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error removing alojamiento from user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all alojamientos selected by a specific user
     * Returns complete alojamiento data with selection timestamp
     * 
     * @param int $user_id
     * @return array|false Returns array of alojamientos on success, false on failure
     */
    public function getUserAlojamientos($user_id) {
        try {
            $query = "SELECT a.id, a.nombre, a.descripcion, a.ubicacion, a.precio, 
                            a.imagen_url, a.created_at, ua.selected_at
                     FROM alojamientos a
                     INNER JOIN " . $this->table . " ua ON a.id = ua.alojamiento_id
                     WHERE ua.user_id = :user_id
                     ORDER BY ua.selected_at DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user alojamientos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if an alojamiento is already selected by a user
     * 
     * @param int $user_id
     * @param int $alojamiento_id
     * @return bool Returns true if selected, false otherwise
     */
    public function isAlojamientoSelected($user_id, $alojamiento_id) {
        try {
            $query = "SELECT id FROM " . $this->table . " 
                     WHERE user_id = :user_id AND alojamiento_id = :alojamiento_id 
                     LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':alojamiento_id', $alojamiento_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error checking if alojamiento is selected: " . $e->getMessage());
            return false;
        }
    }
}
