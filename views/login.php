<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Alojamientos</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Iniciar Sesión</h1>
                <p>Accede a tu cuenta para gestionar tus alojamientos</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="?action=login" class="auth-form">
                <div class="form-group">
                    <label for="username">Usuario o Correo Electrónico</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo isset($username) ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : ''; ?>"
                        required
                        autofocus
                    >
                    <?php if (isset($errors['username'])): ?>
                        <span class="error-message">
                            <?php echo htmlspecialchars($errors['username'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                        required
                    >
                    <?php if (isset($errors['password'])): ?>
                        <span class="error-message">
                            <?php echo htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Iniciar Sesión
                </button>
            </form>

            <div class="auth-footer">
                <p>¿No tienes una cuenta? <a href="?action=register">Regístrate aquí</a></p>
                <p><a href="?action=landing">Volver al inicio</a></p>
            </div>
        </div>
    </div>
</body>
</html>
