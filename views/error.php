<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Alojamientos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="error-container">
        <h1>Error</h1>
        <p><?php echo htmlspecialchars($errorMessage ?? 'Ha ocurrido un error inesperado.'); ?></p>
        <a href="index.php" class="btn">Volver al inicio</a>
    </div>
</body>
</html>
