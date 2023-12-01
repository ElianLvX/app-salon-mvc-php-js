<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina"> Coloca tu nuevo password a continuacion </p>

<?php 
    include_once __DIR__ . '/../templates/alertas.php';
?>

<?php if($error) return; ?>
<form class="formulario" method="POST"> <!-- Se enviar el POST a esta misma pagina para valorar los cambios y tambien por que en la URL va el token si no se pierde -->
    <div class="campo">
        <label for="password"> Password </label>
        <input
            type="password" 
            id="password"
            name="password"
            placeholder="Tu Nuevo Password"
        />
    </div>

    <input type="submit" class="boton" value="Guardar Nuevo Password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Inicar Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes cuenta? Obtener Una.</a>
</div>