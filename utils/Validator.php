<?php

class Validator {
    /**
     * Valida el formato de un email
     * 
     * @param string $email Email a validar
     * @return bool
     */
    public static function validateEmail($email) {
        if (empty($email)) {
            return false;
        }
        
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Valida la fortaleza de una contraseña
     * Requisitos: mínimo 8 caracteres
     * 
     * @param string $password Contraseña a validar
     * @return bool
     */
    public static function validatePassword($password) {
        if (empty($password)) {
            return false;
        }
        
        // Mínimo 8 caracteres
        return strlen($password) >= 8;
    }
    
    /**
     * Sanitiza entrada de usuario para prevenir XSS
     * 
     * @param string $data Datos a sanitizar
     * @return string
     */
    public static function sanitizeInput($data) {
        if (empty($data)) {
            return '';
        }
        
        // Eliminar espacios en blanco al inicio y final
        $data = trim($data);
        
        // Eliminar barras invertidas
        $data = stripslashes($data);
        
        // Convertir caracteres especiales a entidades HTML
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        
        return $data;
    }
    
    /**
     * Valida que los campos requeridos no estén vacíos
     * 
     * @param array $fields Array asociativo de campos a validar ['nombre_campo' => 'valor']
     * @return array Array de errores ['nombre_campo' => 'mensaje de error']
     */
    public static function validateRequired($fields) {
        $errors = [];
        
        foreach ($fields as $fieldName => $fieldValue) {
            if (empty($fieldValue) || trim($fieldValue) === '') {
                $errors[$fieldName] = "El campo {$fieldName} es requerido";
            }
        }
        
        return $errors;
    }
}
