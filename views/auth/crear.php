<div class="contenedor crear">
    <?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

        <form action="/crear" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu nombre">
            </div>
            <div class="campo">
                <label for="email">Correo</label>
                <input type="email" name="email" id="email" placeholder="Tu correo">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Tu contraseña">
            </div>
            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu contraseña">
            </div>

            <input type="submit" value="Crear cuenta" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta?, inicia sesión</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>