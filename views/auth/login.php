<div class="contenedor login">
    <?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>
    

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar sesión</p>

        <?php include_once __DIR__.'/../templates/alertas.php'; ?>

        <form action="/" method="POST" class="formulario" novalidate>
            <div class="campo">
                <label for="email">Correo</label>
                <input type="email" name="email" id="email" placeholder="Tu correo">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Tu contraseña">
            </div>

            <input type="submit" value="Iniciar sesión" class="boton">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta?, crea una</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>