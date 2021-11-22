<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    //  /
    public static function login(Router $router){
        
        //INSTANCIAR EL OBJETO USUARIO
        
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //INSTANCIAR OBJETO CON EL POST
            $usuario = new Usuario($_POST);

            //ALERTAS
            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                //BUSCAR EL USUARIO EN LA BD CON EL CORREO
                $usuario = Usuario::where('email', $usuario->email);

                if (!$usuario || !$usuario->confirmado) {
                    //SI EL CORREO NO EXISTE
                    Usuario::setAlerta('error', 'No hay una cuenta vinculada con ese correo, o tu cuenta no ha sido confirmada');
                }else{
                    //SI EXISTE EL CORREO
                    //VERIFICAR PASSWORD
                    if (!password_verify($_POST['password'], $usuario->password)) {
                        //CONTRASEÑA INCORRECTA
                        Usuario::setAlerta('error', 'Contraseña incorrecta');
                    }else{
                        //INICIAR LA SESION DEL USUARIO
                        //YA EXISTE LA SESION INICIADA DESDE Router.php
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['apellidos'] = $usuario->apellidos;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //REDIRECCIONAR
                        header('Location: /dashboard');
                    }
                        
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar sesión',
            'alertas' => $alertas
        ]);
    }



    //  /logout
    public static function logout(){
        //VACIAR LA SESION
        $_SESSION = [];

        header('Location: /');
    }



    //  /crear
    public static function crear(Router $router){

        //INSTANCIAR EL OBJETO USUARIO
        $usuario = new Usuario;

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //SINCRONIZAR PARA QUE EL FORM NO SE VACÍE EN CASO DE ERRORES
            $usuario->sincronizar($_POST);

            //VALIDAR EL FORMULARIO
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                //COMPROBAR QUE EL CORREO NO EXISTA EN LA BD
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    //ESTE MENSAJE SE QUEDA GUARDADO EN MEMORIA MOMENTANEAMENTE
                    Usuario::setAlerta('error', 'Este correo ya está registrado en otra cuenta');
                    //LIBERAMOS LA MEMORIA Y LO PASAMOS A $alertas
                    $alertas = Usuario::getAlertas();
                }else{
                    //HASHEAR EL PASSWORD
                    $usuario->hashPassword();

                    //ELIMINAR PASSWORD2 DEL OBJETO, YA QUE ACTIVE RECORD TRABAJA CON UN ESPEJO DE LA TABLA EN LA DB
                    unset($usuario->password2);

                    //GENERAR EL TOKEN
                    $usuario->crearToken();

                    //GUARDAR EN LA BD
                    $resultado = $usuario->guardar();

                    //ENVIAR EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    
                    //REDIRECCIONAR
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear mi cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }



    //  /olvide
    public static function olvide(Router $router){
        
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                //BUSCAR AL USUARIO EN LA BD CON EL CORREO
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado === '1') {
                    //GENERAR TOKEN
                    $usuario->crearToken();
                    unset($usuario->password2);//eliminar password2 (no es necesario)

                    //ACTUALIZAR EN LA BD EL TOKEN
                    $usuario->guardar();

                    //ENVIAR EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu correo');
                }else{
                    //SI NO EXISTE EL CORREO
                    Usuario::setAlerta('error', 'No hay una cuenta vinculada con este correo, o tu cuenta no ha sido confirmada');
                }
                
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvidé mi contraseña',
            'alertas' => $alertas
        ]);
    }



    //  /reestablecer
    public static function reestablecer(Router $router){
        
        $alertas = [];
        $mostrar = true;

        //VERIFICAR EL TOKEN
        $token = s($_GET['token']);
        if(!$token) header('Location: /');

        //VERIFICAR QUE EL TOKEN SEA EL DEL USUARIO
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            //si existe el token
            Usuario::setAlerta('error', 'Token no válido');
            $mostrar = false; //quitamos el formulario
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //SINCRONIZAR EL OBJETO CON LA BD
            $usuario->sincronizar($_POST);

            //VALIDAR EL FORMULARIO
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                //HASHEAR EL PASSWORD
                $usuario->hashPassword();

                //ELIMINAR PASSWORD2 DEL OBJETO, YA QUE ACTIVE RECORD TRABAJA CON UN ESPEJO DE LA TABLA EN LA DB
                unset($usuario->password2);

                //ELIMINAR EL TOKEN
                $usuario->token = null;

                //GUARDAR EN LA BD
                $resultado = $usuario->guardar();

                //REDIRECCIONAR
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }



    //  /mensaje
    public static function mensaje(Router $router){
        $router->render('auth/mensaje', [
            'titulo' => 'Instrucciones'
        ]);
    }



    //  /confirmar
    public static function confirmar(Router $router){

        $alertas = [];

        //LEER EL TOKEN DE LA URL
        $token = s($_GET['token']);
        if (!$token) header('Location: /');
        
        //BUSCAR AL USUARIO POR EL TOKEN EN LA BD
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //NO SE ENCUENTRA UN USUARIO CON ESE TOKEN
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //CONFIRMAR LA CUENTA
            $usuario->confirmado = 1;//cambiar confirmado
            $usuario->token = null;//eliminar el token
            unset($usuario->password2);//eliminar el password2

            //ACTUALIZAR EN LA BD
            $usuario->guardar();

            // MENSAJE DE EXITO
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta confirmada',
            'alertas' => $alertas
        ]);
    }
}
