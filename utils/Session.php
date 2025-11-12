<?php

class Session {
    /**
     * Inicia una sesión segura
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Configuración de seguridad para la sesión
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
        }
    }
    
    /**
     * Establece un valor en la sesión
     * 
     * @param string $key Clave del valor
     * @param mixed $value Valor a almacenar
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Obtiene un valor de la sesión
     * 
     * @param string $key Clave del valor
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    public static function get($key, $default = null) {
        self::start();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    /**
     * Verifica si hay un usuario autenticado
     * 
     * @return bool
     */
    public static function isAuthenticated() {
        self::start();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Obtiene el ID del usuario actual
     * 
     * @return int|null
     */
    public static function getUserId() {
        self::start();
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Obtiene el rol del usuario actual
     * 
     * @return string|null
     */
    public static function getUserRole() {
        self::start();
        return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }
    
    /**
     * Destruye la sesión completamente
     */
    public static function destroy() {
        self::start();
        
        // Limpiar todas las variables de sesión
        $_SESSION = array();
        
        // Eliminar la cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destruir la sesión
        session_destroy();
    }
}
