# Database Setup Instructions

This directory contains the database schema and initialization scripts for the Alojamientos CRUD Application.

## Files

- `schema.sql` - Database schema with table definitions
- `seed_data.sql` - SQL script to insert initial data
- `seed_data.php` - PHP script to insert initial data (recommended)

## Setup Steps

### 1. Create the Database and Tables

First, create the database and tables using the schema file:

**Option A: Using MySQL command line**
```bash
mysql -u root -p < database/schema.sql
```

**Option B: Using phpMyAdmin**
1. Open phpMyAdmin in your browser
2. Click on "SQL" tab
3. Copy and paste the content of `schema.sql`
4. Click "Go" to execute

### 2. Initialize Data

After creating the tables, populate them with initial data:

**Option A: Using PHP script (Recommended)**
```bash
php database/seed_data.php
```

**Option B: Using SQL script**
```bash
mysql -u root -p alojamientos_db < database/seed_data.sql
```

**Option C: Using phpMyAdmin**
1. Open phpMyAdmin and select the `alojamientos_db` database
2. Click on "SQL" tab
3. Copy and paste the content of `seed_data.sql`
4. Click "Go" to execute

## Initial Data

### Admin User

After running the initialization script, you'll have an admin user:

- **Username:** `admin`
- **Email:** `admin@alojamientos.com`
- **Password:** `admin123`

⚠️ **Important:** Change the admin password after first login in a production environment!

### Sample Alojamientos

The script creates 10 sample accommodations:

1. Casa de Playa Paraíso - Cancún, Quintana Roo ($2,500)
2. Cabaña Rústica en la Montaña - Valle de Bravo ($1,200)
3. Departamento Moderno Centro - Ciudad de México ($1,800)
4. Villa Colonial con Jardín - San Miguel de Allende ($3,500)
5. Loft Industrial Downtown - Guadalajara ($1,600)
6. Bungalow Tropical con Piscina - Tulum ($2,200)
7. Penthouse con Vista Panorámica - Monterrey ($4,000)
8. Casa Campestre con Viñedo - Ensenada ($1,900)
9. Estudio Minimalista Playa - Playa del Carmen ($1,400)
10. Hacienda Histórica Restaurada - Mérida ($5,000)

## Troubleshooting

### Connection Issues

If you get connection errors, verify your database configuration in `config/database.php`:

```php
private $host = 'localhost';
private $db_name = 'alojamientos_db';
private $username = 'root';
private $password = '';
```

### Permission Issues

If you get permission errors, ensure your MySQL user has the necessary privileges:

```sql
GRANT ALL PRIVILEGES ON alojamientos_db.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
```

### Running Scripts Multiple Times

Both initialization scripts are safe to run multiple times. They will:
- Skip inserting the admin user if it already exists
- Skip inserting alojamientos that already exist (by name)

## Verification

After running the initialization scripts, you can verify the data:

```sql
-- Check admin user
SELECT * FROM users WHERE role = 'admin';

-- Count alojamientos
SELECT COUNT(*) as total FROM alojamientos;

-- View all alojamientos
SELECT id, nombre, ubicacion, precio FROM alojamientos ORDER BY id;
```

## Next Steps

After setting up the database:

1. Configure your database credentials in `config/database.php`
2. Access the application at `http://localhost/alojamientos-crud-app/public/`
3. Log in with the admin credentials to add more accommodations
4. Create regular user accounts to test the full functionality
