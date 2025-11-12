-- ============================================================================
-- Data Initialization Script for Alojamientos CRUD Application
-- ============================================================================
-- 
-- INSTRUCTIONS FOR EXECUTION:
-- 
-- Option 1: Execute via MySQL command line
--   mysql -u root -p alojamientos_db < database/seed_data.sql
-- 
-- Option 2: Execute via phpMyAdmin
--   1. Open phpMyAdmin in your browser
--   2. Select the 'alojamientos_db' database
--   3. Click on the 'SQL' tab
--   4. Copy and paste this entire file content
--   5. Click 'Go' to execute
-- 
-- Option 3: Execute via PHP script
--   php database/seed_data.php
-- 
-- NOTES:
-- - This script assumes the database 'alojamientos_db' already exists
-- - This script assumes the schema has been created (run schema.sql first)
-- - The admin password is 'admin123' (hashed with bcrypt)
-- - You can run this script multiple times safely (uses INSERT IGNORE)
-- 
-- ============================================================================

USE alojamientos_db;

-- ============================================================================
-- Insert Initial Admin User
-- ============================================================================
-- Username: admin
-- Email: admin@alojamientos.com
-- Password: admin123
-- Role: admin
-- 
-- Password hash generated with: password_hash('admin123', PASSWORD_BCRYPT)
-- ============================================================================

INSERT IGNORE INTO users (username, email, password_hash, role) VALUES
('admin', 'admin@alojamientos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- ============================================================================
-- Insert Sample Alojamientos (Accommodations)
-- ============================================================================
-- These are example accommodations to populate the system
-- ============================================================================

INSERT IGNORE INTO alojamientos (id, nombre, descripcion, ubicacion, precio, imagen_url) VALUES
(1, 'Casa de Playa Paraíso', 'Hermosa casa frente al mar con 3 habitaciones, piscina privada y vista panorámica al océano. Perfecta para familias.', 'Cancún, Quintana Roo', 2500.00, 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=400'),

(2, 'Cabaña Rústica en la Montaña', 'Acogedora cabaña de madera en las montañas con chimenea, ideal para escapadas románticas. Incluye desayuno.', 'Valle de Bravo, Estado de México', 1200.00, 'https://images.unsplash.com/photo-1587061949409-02df41d5e562?w=400'),

(3, 'Departamento Moderno Centro', 'Departamento completamente equipado en el corazón de la ciudad. 2 habitaciones, WiFi de alta velocidad y estacionamiento.', 'Ciudad de México, CDMX', 1800.00, 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400'),

(4, 'Villa Colonial con Jardín', 'Elegante villa colonial con amplio jardín, 4 habitaciones, alberca y área de BBQ. Perfecta para eventos familiares.', 'San Miguel de Allende, Guanajuato', 3500.00, 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400'),

(5, 'Loft Industrial Downtown', 'Espacioso loft de estilo industrial con techos altos, cocina gourmet y terraza privada. Zona trendy de la ciudad.', 'Guadalajara, Jalisco', 1600.00, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400'),

(6, 'Bungalow Tropical con Piscina', 'Bungalow rodeado de vegetación tropical con piscina privada, hamacas y área de yoga. Ambiente zen y relajante.', 'Tulum, Quintana Roo', 2200.00, 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400'),

(7, 'Penthouse con Vista Panorámica', 'Lujoso penthouse en piso 20 con vista de 360 grados, jacuzzi en terraza y acabados de lujo. 3 habitaciones.', 'Monterrey, Nuevo León', 4000.00, 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400'),

(8, 'Casa Campestre con Viñedo', 'Encantadora casa campestre rodeada de viñedos, con bodega de vinos, 2 habitaciones y cocina rústica equipada.', 'Ensenada, Baja California', 1900.00, 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400'),

(9, 'Estudio Minimalista Playa', 'Estudio minimalista a pasos de la playa, diseño contemporáneo, ideal para parejas. Incluye bicicletas.', 'Playa del Carmen, Quintana Roo', 1400.00, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400'),

(10, 'Hacienda Histórica Restaurada', 'Majestuosa hacienda del siglo XIX completamente restaurada, 6 habitaciones, capilla, caballerizas y amplios jardines.', 'Mérida, Yucatán', 5000.00, 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400');

-- ============================================================================
-- Verification Queries
-- ============================================================================
-- Uncomment the following lines to verify the data was inserted correctly
-- ============================================================================

-- SELECT * FROM users WHERE role = 'admin';
-- SELECT COUNT(*) as total_alojamientos FROM alojamientos;
-- SELECT id, nombre, ubicacion, precio FROM alojamientos ORDER BY id;

-- ============================================================================
-- End of Initialization Script
-- ============================================================================
