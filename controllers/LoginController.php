<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    //  /
    public static function login(Router $router){
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            # code...
        }

        $router->render('auth/login', [
            'titulo' => 'Iniciar sesión'
        ]);
    }

    //  /logout
    public static function logout(){
        echo 'Desde logout';
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
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            # code...
        }

        $router->render('auth/olvide', [
            'titulo' => 'Olvidé mi contraseña'
        ]);
    }

    //  /reestablecer
    public static function reestablecer(Router $router){
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            # code...
        }

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer contraseña'
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
