<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Alojamientos</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Crear Cuenta</h1>
                <p>Regístrate para seleccionar y gestionar tus alojamientos favoritos</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="?action=register" class="auth-form">
                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo isset($username) ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : ''; ?>"
                        required
                        autofocus
                        minlength="3"
                        maxlength="50"
                    >
                    <?php if (isset($errors['username'])): ?>
                        <span class="error-message">
                            <?php echo htmlspecialchars($errors['username'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                    <small class="form-text">Mínimo 3 caracteres, máximo 50</small>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                        value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : ''; ?>"
                        required
                        maxlength="100"
                    >
                    <?php if (isset($errors['email'])): ?>
                        <span class="error-message">
                            <?php echo htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8'); ?>
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
                        minlength="8"
                    >
                    <?php if (isset($errors['password'])): ?>
                        <span class="error-message">
                            <?php echo htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                    <small class="form-text">Mínimo 8 caracteres</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>"
                        required
                        minlength="8"
                    >
                    <?php if (isset($errors['confirm_password'])): ?>
                        <span class="error-message">
                            <?php echo htmlspecialchars($errors['confirm_password'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Crear Cuenta
                </button>
            </form>

            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="?action=login">Inicia sesión aquí</a></p>
                <p><a href="?action=landing">Volver al inicio</a></p>
            </div>
        </div>
    </div>
</body>
</html>
