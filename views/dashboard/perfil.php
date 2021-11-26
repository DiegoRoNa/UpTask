<?php include_once __DIR__.'/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__.'/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace">Cambiar contraseña</a>

    <form class="formulario" action="" method="POST">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?=$usuario->nombre;?>" placeholder="Tu nombre">
        </div>

        <div class="campo">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" value="<?=$usuario->apellidos;?>" placeholder="Tus apellidos">
        </div>

        <div class="campo">
            <label for="email">Correo</label>
            <input type="email" name="email" id="email" value="<?=$usuario->email;?>" placeholder="Tu correo">
        </div>

        <input type="submit" value="Guardar cambios">
    </form>
    
</div>

<?php include_once __DIR__.'/footer-dashboard.php'; ?>