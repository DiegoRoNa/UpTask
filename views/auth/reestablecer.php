<div class="contenedor reestablecer">
    <?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nueva contraseña</p>

        <?php include_once __DIR__.'/../templates/alertas.php'; ?>

        <?php if($mostrar): ?>
            <form method="POST" class="formulario" novalidate>
                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Tu nueva contraseña">
                </div>

                <div class="campo">
                    <label for="password2">Repetir Contraseña</label>
                    <input type="password" name="password2" id="password2" placeholder="Repite tu contraseña">
                </div>

                <input type="submit" value="Guardar contraseña" class="boton">
            </form>
        <?php endif; ?>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta?, inicia sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta?, crea una</a>
        </div>
    </div>
</div>