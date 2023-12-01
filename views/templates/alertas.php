<?php
    foreach ($alertas as $key => $mensajes) : // Basicamente estamos iterando un array asosiativo (OJO no es un array normal o indexado)
        foreach($mensajes as $mensaje) : // Y mensajes a su vez
?>

<div class="alerta <?= $key; ?>"> <!-- hay dos tipos de alerta 'error' y 'exito' lo define el backend -->
    <?= $mensaje; ?>
</div>

<?php 
        endforeach;
    endforeach;
?>