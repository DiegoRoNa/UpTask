<div class="contenedor olvide">
    <?php include_once __DIR__.'/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso UpTask</p>

        <form action="/olvide" method="POST" class="formulario">
        
            <div class="campo">
                <label for="email">Correo</label>
                <input type="email" name="email" id="email" placeholder="Tu correo">
            </div>

            <input type="submit" value="Enviar instrucciones" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta?, inicia sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta?, crea una</a>
        </div>
    </div>
</div>