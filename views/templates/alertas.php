<?php
//SON 2 FOREACH YA QUE SE ESTÁN AGREGANDO LOS ERRORES A UN ARREGLO LLAMADO alertas
//Y SE LE ESTÁ AGREGANDO OTRO ARREGLO LLAMADO error
//ENTONCES HAY QUE RECORRER 2 ARREGLOS, SON 2 FOREACH

foreach($alertas as $key => $alerta): ?>
    <?php foreach($alerta as $mensaje): ?>
        <div class="alerta <?=$key;?>"><?=$mensaje;?></div>
    <?php endforeach; ?>
<?php endforeach; ?>

