<h1 class="nombre-pagina">Actualizar Servicios</h1>
<p class="descripcion-pagina">Modifica los valores del formualirio</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
    include_once __DIR__ . '/../templates/alertas.php';
?>


<!-- 
El action en este form se le quita debido a que se esta enviando a esta misma pagina
es decir a la ruta : http://appsalonmvc.localhost/servicios/actualizar?id=1
por que esta ya tiene el ID del Registro
si no va a peder la referencia.
-->
<form method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?>
    <input type="submit" class="boton" value="Actualizar">
</form>