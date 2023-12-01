<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <for class="formulario">

        <div class="campo">
            <label for="fechad">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?= $fecha; ?>" ;>
        </div>

    </for>
</div>

<?php
if (count($citas) === 0) {
    echo "<h2>No Hay Citas en esta Fecha</h2>";
}
?>

<div class="citas-admin">
    <!-- 
        * Explicacion detallada del Siguiente bloque de PHP

        $idCita :- Sirve como una variable de referencia guarda el ID de la cita de la primera iteracion y la comparara con el ID de la siguiente iteracion.
        if ($idCita !== $cita->id) :- Si $idCita(referencia de id cita anteriror) es diferente al el id de la cita actual entonces Imprime el contenido de <li>
        
        <p class="servicio"> < ?php $cita->servicio . ": $" . $cita->precio; ?> </p> :- Esta Imprimira todos los servicios seleccionados por el usuario es decir
            que como tal $cita nos trae el usuario repetido pero en realidad nos lo trae repetido el nombre por que esta iterando tambien los servicios
            por eso es que la condicional de if($idCita !== $cita->id) evita que se imprima varias veces el mismo usuario y al colocar
            la imprecion de cita->servicio fuera de este if si se imprimiran correctamen solo los servicios.

        En conclusion: lo que en realidad se esta iterando son los Servicios hechos por el usuario pero en la consulta SQL tambien se estan llamando
                        el nombre, email, hora, etc (debido a los JOIN). esos datos solo los necesitamos una vez es por eso que esta el if, pero tambien necesitamos los servicios
                        que traen eso registros por eso los imprimimos afuera del if, si con esto no se entiende verificar la consulta SQL en AdminController.php

            En pocas palabras en la consulta viene pegado los datos del cliente y los servicios que quiere pero cada que se quire ver un servicio pues tambien 
            viene el nombre del cliente para partilos por decirlo de otra manera se usa el if con el idCita de referencia si no es igual lo imprime 
            y vuelve iterar con el mismo idCita ahora para imprimir los servicio pero como el idCita seguira siendo el mismo pues no entrara en el if para que
            se vuelvan a duplicar los datos del cliente basicamente se partio los datos de la consulta SQL.
    -->

    <ul class="citas">
        <?php
        $idCita = null; // Este contiene el ultimo ID de cita iterado
        foreach ($citas as $key => $cita) {
            if ($idCita !== $cita->id) { // Si el ultimo id guardado es diferente al id de la iteracion actual se muestra si no pues no
                $total = 0; // esta es la variable que contendra la suma, se coloca aqui por que aqui solo se entra una vez por ID de Cita(si esta afuera del if se estaria reseteando)
        ?>
                <li> <!-- Si $idCita es diferente a la cita iterada actualmente no se muestra ninguna datos de <li> -->
                    <p>ID: <span><?php echo $cita->id ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
                    <p>Email: <span><?php echo $cita->email ?></span></p>
                    <p>Telefono: <span><?php echo $cita->telefono ?></span></p>

                    <h3>Servicios</h3>
                <?php
                $idCita = $cita->id; // este guarda el ID actual de la cita y en la proxima iteracion lo compara con el ID que se esta ejecutando en ese momento (es basicamente un referencia)
            } // Fin de IF 
            // Aqui esta la suma de los servicios
            $total += $cita->precio; // Afuera del IF por que dentro del If solo se ejecuta una sola vez
                ?>
                <!-- Este esta afuera del IF por que se estan recorriendo todos los servicios que el usuario selecciono -->
                <p class="servicio"><?php echo $cita->servicio . ": $" . $cita->precio; ?></p>

                <?php
                // estos verifican si la id actual es diferente a la id que viene en la siguiente iteracion
                // Es decir verifican si el id que viene es diferente al actual, si es asi ejecutaremos la suma de los precios del servicio
                $actual = $cita->id; // Id Actual
                $proximo = $citas[$key + 1]->id ?? 0; // Id actual + 1

                if (esUltimo($actual, $proximo)) { ?>
                    <p class="total">Total: <span><?= $total; ?></span></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?= $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
            <?php }
            } // Fin de ForEach
            ?>
                </li>
    </ul>
</div>

<?php
// Esta variable se define en layout.php (es el master page)
$script = "<script src='build/js/buscador.js'></script>";
?>