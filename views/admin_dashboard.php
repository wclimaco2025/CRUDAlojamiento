<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Alojamientos</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Dashboard Administrador</h1>
            <nav class="nav">
                <span class="user-welcome">
                    Bienvenido, <?php echo htmlspecialchars(Session::get('username', 'Administrador'), ENT_QUOTES, 'UTF-8'); ?>
                </span>
                <a href="?action=logout" class="btn btn-secondary">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <main class="container dashboard-container">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para Agregar Nuevo Alojamiento -->
        <section class="dashboard-section">
            <h2>Agregar Nuevo Alojamiento</h2>
            
            <form method="POST" action="?action=createAlojamiento" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre del Alojamiento *</label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            class="form-control <?php echo isset($errors['nombre']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo isset($formData['nombre']) ? htmlspecialchars($formData['nombre'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            required
                            maxlength="100"
                        >
                        <?php if (isset($errors['nombre'])): ?>
                            <span class="error-message">
                                <?php echo htmlspecialchars($errors['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="ubicacion">Ubicación *</label>
                        <input 
                            type="text" 
                            id="ubicacion" 
                            name="ubicacion" 
                            class="form-control <?php echo isset($errors['ubicacion']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo isset($formData['ubicacion']) ? htmlspecialchars($formData['ubicacion'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            required
                            maxlength="200"
                        >
                        <?php if (isset($errors['ubicacion'])): ?>
                            <span class="error-message">
                                <?php echo htmlspecialchars($errors['ubicacion'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea 
                        id="descripcion" 
                        name="descripcion" 
                        class="form-control <?php echo isset($errors['descripcion']) ? 'is-invalid' : ''; ?>"
                        rows="4"
                        required
                    ><?php echo isset($formData['descripcion']) ? htmlspecialchars($formData['descripcion'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    <?php if (isset($errors['descripcion'])): ?>
                        <span class="error-message">
                            <?php echo htmlspecialchars($errors['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="precio">Precio por Noche *</label>
                        <input 
                            type="number" 
                            id="precio" 
                            name="precio" 
                            class="form-control <?php echo isset($errors['precio']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo isset($formData['precio']) ? htmlspecialchars($formData['precio'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            step="0.01"
                            min="0.01"
                            required
                        >
                        <?php if (isset($errors['precio'])): ?>
                            <span class="error-message">
                                <?php echo htmlspecialchars($errors['precio'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="imagen_url">URL de Imagen</label>
                        <input 
                            type="url" 
                            id="imagen_url" 
                            name="imagen_url" 
                            class="form-control <?php echo isset($errors['imagen_url']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo isset($formData['imagen_url']) ? htmlspecialchars($formData['imagen_url'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            maxlength="255"
                            placeholder="https://ejemplo.com/imagen.jpg"
                        >
                        <?php if (isset($errors['imagen_url'])): ?>
                            <span class="error-message">
                                <?php echo htmlspecialchars($errors['imagen_url'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        <?php endif; ?>
                        <small class="form-text">Opcional - Deja en blanco si no tienes una imagen</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Agregar Alojamiento
                </button>
            </form>
        </section>

        <!-- Lista de Todos los Alojamientos -->
        <section class="dashboard-section">
            <h2>Todos los Alojamientos</h2>
            
            <?php if (isset($alojamientos) && count($alojamientos) > 0): ?>
                <div class="alojamientos-grid">
                    <?php foreach ($alojamientos as $alojamiento): ?>
                        <div class="alojamiento-card admin-view">
                            <?php if (!empty($alojamiento['imagen_url'])): ?>
                                <img src="<?php echo htmlspecialchars($alojamiento['imagen_url'], ENT_QUOTES, 'UTF-8'); ?>" 
                                     alt="<?php echo htmlspecialchars($alojamiento['nombre'], ENT_QUOTES, 'UTF-8'); ?>" 
                                     class="alojamiento-image">
                            <?php else: ?>
                                <div class="alojamiento-image-placeholder">
                                    <span>Sin imagen</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="alojamiento-content">
                                <h3 class="alojamiento-nombre">
                                    <?php echo htmlspecialchars($alojamiento['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </h3>
                                
                                <p class="alojamiento-ubicacion">
                                    <svg class="icon" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0C5.2 0 3 2.2 3 5c0 3.9 5 11 5 11s5-7.1 5-11c0-2.8-2.2-5-5-5zm0 7.5c-1.4 0-2.5-1.1-2.5-2.5S6.6 2.5 8 2.5s2.5 1.1 2.5 2.5S9.4 7.5 8 7.5z"/>
                                    </svg>
                                    <?php echo htmlspecialchars($alojamiento['ubicacion'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                                
                                <?php if (!empty($alojamiento['descripcion'])): ?>
                                    <p class="alojamiento-descripcion">
                                        <?php echo htmlspecialchars($alojamiento['descripcion'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="alojamiento-footer">
                                    <span class="alojamiento-precio">
                                        $<?php echo htmlspecialchars(number_format($alojamiento['precio'], 2), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <span class="alojamiento-id">
                                        ID: <?php echo htmlspecialchars($alojamiento['id'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </div>
                                
                                <?php if (isset($alojamiento['created_at'])): ?>
                                    <small class="alojamiento-date">
                                        Creado: <?php echo htmlspecialchars(date('d/m/Y', strtotime($alojamiento['created_at'])), ENT_QUOTES, 'UTF-8'); ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>No hay alojamientos en el sistema todavía.</p>
                    <p>Usa el formulario de arriba para agregar el primer alojamiento.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Alojamientos. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
