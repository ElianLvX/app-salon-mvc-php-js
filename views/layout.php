<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css"> <!-- OJO LA PRIMERA DIAGONAL / ES IMPORTANTE -->
</head>
<body>

    <div class="contenedor-app">
        <div class="imagen"></div>
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>

    <?php
        // Solamente donde exista la variable $script se va a imprimir y donde no pues estara vacia (evita errores)
        echo $script ?? '';
    ?>

</body>
</html>