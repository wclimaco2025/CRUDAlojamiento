<?php
/**
 * ============================================================================
 * PHP Data Initialization Script for Alojamientos CRUD Application
 * ============================================================================
 * 
 * INSTRUCTIONS FOR EXECUTION:
 * 
 * Run this script from the command line:
 *   php database/seed_data.php
 * 
 * Or run it from your browser:
 *   http://localhost/alojamientos-crud-app/database/seed_data.php
 * 
 * NOTES:
 * - This script assumes the database 'alojamientos_db' already exists
 * - This script assumes the schema has been created (run schema.sql first)
 * - The admin password is 'admin123'
 * - You can run this script multiple times safely
 * 
 * ============================================================================
 */

// Include database configuration
require_once __DIR__ . '/../config/database.php';

echo "=============================================================\n";
echo "Data Initialization Script\n";
echo "=============================================================\n\n";

try {
    // Get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Failed to connect to database");
    }
    
    echo "✓ Database connection established\n\n";
    
    // ========================================================================
    // Insert Admin User
    // ========================================================================
    echo "Inserting admin user...\n";
    
    $adminUsername = 'admin';
    $adminEmail = 'admin@alojamientos.com';
    $adminPassword = 'admin123';
    $adminPasswordHash = password_hash($adminPassword, PASSWORD_BCRYPT);
    
    // Check if admin already exists
    $checkQuery = "SELECT id FROM users WHERE username = :username OR email = :email";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':username', $adminUsername);
    $checkStmt->bindParam(':email', $adminEmail);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        echo "  ⚠ Admin user already exists, skipping...\n";
    } else {
        $insertUserQuery = "INSERT INTO users (username, email, password_hash, role) 
                           VALUES (:username, :email, :password_hash, 'admin')";
        $insertUserStmt = $db->prepare($insertUserQuery);
        $insertUserStmt->bindParam(':username', $adminUsername);
        $insertUserStmt->bindParam(':email', $adminEmail);
        $insertUserStmt->bindParam(':password_hash', $adminPasswordHash);
        
        if ($insertUserStmt->execute()) {
            echo "  ✓ Admin user created successfully\n";
            echo "    Username: {$adminUsername}\n";
            echo "    Email: {$adminEmail}\n";
            echo "    Password: {$adminPassword}\n";
        } else {
            echo "  ✗ Failed to create admin user\n";
        }
    }
    
    echo "\n";
    
    // ========================================================================
    // Insert Sample Alojamientos
    // ========================================================================
    echo "Inserting sample alojamientos...\n";
    
    $alojamientos = [
        [
            'nombre' => 'Casa de Playa Paraíso',
            'descripcion' => 'Hermosa casa frente al mar con 3 habitaciones, piscina privada y vista panorámica al océano. Perfecta para familias.',
            'ubicacion' => 'Cancún, Quintana Roo',
            'precio' => 2500.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=400'
        ],
        [
            'nombre' => 'Cabaña Rústica en la Montaña',
            'descripcion' => 'Acogedora cabaña de madera en las montañas con chimenea, ideal para escapadas románticas. Incluye desayuno.',
            'ubicacion' => 'Valle de Bravo, Estado de México',
            'precio' => 1200.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1587061949409-02df41d5e562?w=400'
        ],
        [
            'nombre' => 'Departamento Moderno Centro',
            'descripcion' => 'Departamento completamente equipado en el corazón de la ciudad. 2 habitaciones, WiFi de alta velocidad y estacionamiento.',
            'ubicacion' => 'Ciudad de México, CDMX',
            'precio' => 1800.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400'
        ],
        [
            'nombre' => 'Villa Colonial con Jardín',
            'descripcion' => 'Elegante villa colonial con amplio jardín, 4 habitaciones, alberca y área de BBQ. Perfecta para eventos familiares.',
            'ubicacion' => 'San Miguel de Allende, Guanajuato',
            'precio' => 3500.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400'
        ],
        [
            'nombre' => 'Loft Industrial Downtown',
            'descripcion' => 'Espacioso loft de estilo industrial con techos altos, cocina gourmet y terraza privada. Zona trendy de la ciudad.',
            'ubicacion' => 'Guadalajara, Jalisco',
            'precio' => 1600.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400'
        ],
        [
            'nombre' => 'Bungalow Tropical con Piscina',
            'descripcion' => 'Bungalow rodeado de vegetación tropical con piscina privada, hamacas y área de yoga. Ambiente zen y relajante.',
            'ubicacion' => 'Tulum, Quintana Roo',
            'precio' => 2200.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400'
        ],
        [
            'nombre' => 'Penthouse con Vista Panorámica',
            'descripcion' => 'Lujoso penthouse en piso 20 con vista de 360 grados, jacuzzi en terraza y acabados de lujo. 3 habitaciones.',
            'ubicacion' => 'Monterrey, Nuevo León',
            'precio' => 4000.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400'
        ],
        [
            'nombre' => 'Casa Campestre con Viñedo',
            'descripcion' => 'Encantadora casa campestre rodeada de viñedos, con bodega de vinos, 2 habitaciones y cocina rústica equipada.',
            'ubicacion' => 'Ensenada, Baja California',
            'precio' => 1900.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400'
        ],
        [
            'nombre' => 'Estudio Minimalista Playa',
            'descripcion' => 'Estudio minimalista a pasos de la playa, diseño contemporáneo, ideal para parejas. Incluye bicicletas.',
            'ubicacion' => 'Playa del Carmen, Quintana Roo',
            'precio' => 1400.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400'
        ],
        [
            'nombre' => 'Hacienda Histórica Restaurada',
            'descripcion' => 'Majestuosa hacienda del siglo XIX completamente restaurada, 6 habitaciones, capilla, caballerizas y amplios jardines.',
            'ubicacion' => 'Mérida, Yucatán',
            'precio' => 5000.00,
            'imagen_url' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400'
        ]
    ];
    
    $insertQuery = "INSERT INTO alojamientos (nombre, descripcion, ubicacion, precio, imagen_url) 
                    VALUES (:nombre, :descripcion, :ubicacion, :precio, :imagen_url)";
    $insertStmt = $db->prepare($insertQuery);
    
    $insertedCount = 0;
    $skippedCount = 0;
    
    foreach ($alojamientos as $alojamiento) {
        // Check if alojamiento already exists
        $checkQuery = "SELECT id FROM alojamientos WHERE nombre = :nombre";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':nombre', $alojamiento['nombre']);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            echo "  ⚠ '{$alojamiento['nombre']}' already exists, skipping...\n";
            $skippedCount++;
            continue;
        }
        
        // Insert alojamiento
        $insertStmt->bindParam(':nombre', $alojamiento['nombre']);
        $insertStmt->bindParam(':descripcion', $alojamiento['descripcion']);
        $insertStmt->bindParam(':ubicacion', $alojamiento['ubicacion']);
        $insertStmt->bindParam(':precio', $alojamiento['precio']);
        $insertStmt->bindParam(':imagen_url', $alojamiento['imagen_url']);
        
        if ($insertStmt->execute()) {
            echo "  ✓ '{$alojamiento['nombre']}' inserted\n";
            $insertedCount++;
        } else {
            echo "  ✗ Failed to insert '{$alojamiento['nombre']}'\n";
        }
    }
    
    echo "\n";
    echo "Summary:\n";
    echo "  - Alojamientos inserted: {$insertedCount}\n";
    echo "  - Alojamientos skipped: {$skippedCount}\n";
    
    // ========================================================================
    // Display Summary
    // ========================================================================
    echo "\n=============================================================\n";
    echo "Initialization Complete!\n";
    echo "=============================================================\n\n";
    
    // Count total records
    $countUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $countAlojamientos = $db->query("SELECT COUNT(*) FROM alojamientos")->fetchColumn();
    
    echo "Database Statistics:\n";
    echo "  - Total users: {$countUsers}\n";
    echo "  - Total alojamientos: {$countAlojamientos}\n";
    
    echo "\n";
    echo "Admin Credentials:\n";
    echo "  - Username: admin\n";
    echo "  - Email: admin@alojamientos.com\n";
    echo "  - Password: admin123\n";
    
    echo "\n";
    echo "You can now access the application!\n";
    echo "=============================================================\n";
    
} catch (PDOException $e) {
    echo "\n✗ Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
