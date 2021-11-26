<?php include_once __DIR__.'/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__.'/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver</a>

    <form class="formulario" action="/cambiar-password" method="POST">
        <div class="campo">
            <label for="password_actual">Contraseña actual</label>
            <input type="password" name="password_actual" id="password_actual" placeholder="Tu contraseña actual">
        </div>

        <div class="campo">
            <label for="password_nuevo">Contraseña nueva</label>
            <input type="password" name="password_nuevo" id="password_nuevo" placeholder="Tus contraseña nueva">
        </div>

        <input type="submit" value="Guardar cambios">
    </form>
    
</div>

<?php include_once __DIR__.'/footer-dashboard.php'; ?>