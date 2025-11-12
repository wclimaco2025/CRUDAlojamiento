<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Dashboard - Alojamientos</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Mi Dashboard</h1>
            <nav class="nav">
                <span class="user-welcome">
                    Bienvenido, <?php echo htmlspecialchars(Session::get('username', 'Usuario'), ENT_QUOTES, 'UTF-8'); ?>
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

        <!-- Mis Alojamientos Seleccionados -->
        <section class="dashboard-section">
            <h2>Mis Alojamientos Seleccionados</h2>
            
            <?php if (isset($selectedAlojamientos) && count($selectedAlojamientos) > 0): ?>
                <div class="alojamientos-grid">
                    <?php foreach ($selectedAlojamientos as $alojamiento): ?>
                        <div class="alojamiento-card selected">
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
                                    <form method="POST" action="?action=removeAlojamiento" class="inline-form">
                                        <input type="hidden" name="alojamiento_id" value="<?php echo htmlspecialchars($alojamiento['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este alojamiento?');">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>No has seleccionado ningún alojamiento todavía.</p>
                    <p>Explora los alojamientos disponibles abajo y selecciona tus favoritos.</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- Todos los Alojamientos Disponibles -->
        <section class="dashboard-section">
            <h2>Todos los Alojamientos Disponibles</h2>
            
            <?php 
            // Filter out already selected alojamientos
            $availableAlojamientos = array_filter($allAlojamientos ?? [], function($alojamiento) use ($selectedIds) {
                return !in_array($alojamiento['id'], $selectedIds);
            });
            ?>
            
            <?php if (count($availableAlojamientos) > 0): ?>
                <div class="alojamientos-grid">
                    <?php foreach ($availableAlojamientos as $alojamiento): ?>
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
                                    <form method="POST" action="?action=selectAlojamiento" class="inline-form">
                                        <input type="hidden" name="alojamiento_id" value="<?php echo htmlspecialchars($alojamiento['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Seleccionar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
