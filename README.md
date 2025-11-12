# Alojamientos CRUD Application

Aplicación web de gestión de alojamientos desarrollada en PHP y MySQL.

## Estructura del Proyecto

```
/alojamientos-crud-app
├── /config              # Configuración de base de datos
├── /models              # Modelos de datos
├── /controllers         # Controladores de lógica de negocio
├── /views               # Vistas HTML/PHP
├── /public              # Archivos públicos (CSS, JS, imágenes)
│   ├── /css
│   ├── /js
│   └── /images
├── /utils               # Utilidades (Session, Validator)
└── /database            # Scripts SQL
```

## Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) o PHP built-in server
- Extensiones PHP: PDO, pdo_mysql

## Instalación

1. **Crear la base de datos:**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

2. **Configurar la conexión a la base de datos:**
   Editar `config/database.php` con tus credenciales de MySQL si es necesario.

3. **Iniciar el servidor de desarrollo:**
   ```bash
   php -S localhost:8000 -t public
   ```

4. **Acceder a la aplicación:**
   Abrir el navegador en `http://localhost:8000`

## Características

- Visualización de alojamientos en landing page
- Registro e inicio de sesión de usuarios
- Dashboard para usuarios regulares con selección de alojamientos
- Dashboard para administradores con capacidad de agregar alojamientos
- Protección contra inyección SQL y XSS
- Gestión de sesiones segura

## Seguridad

- Contraseñas hasheadas con bcrypt
- Prepared statements para todas las consultas SQL
- Sanitización de entradas y salidas
- Validación de datos en servidor
- Control de acceso basado en roles
