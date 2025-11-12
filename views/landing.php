<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alojamientos - Explora y Selecciona</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Alojamientos</h1>
            <nav class="nav">
                <a href="?action=login" class="btn btn-secondary">Iniciar Sesión</a>
                <a href="?action=register" class="btn btn-primary">Registrarse</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <h2>Explora Nuestros Alojamientos</h2>
            <p>Descubre los mejores lugares para hospedarte. Crea una cuenta para seleccionar tus favoritos.</p>
        </section>

        <section class="alojamientos-grid">
            <?php if (isset($alojamientos) && count($alojamientos) > 0): ?>
                <?php foreach ($alojamientos as $alojamiento): ?>
                    <div class="alojamiento-card">
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
                                <span class="alojamiento-precio-label">por noche</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No hay alojamientos disponibles en este momento.</p>
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
