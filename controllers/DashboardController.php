<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController{

    //  /dashboard
    public static function index(Router $router){

        //YA ESTA LA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAuth();

        $id = $_SESSION['id'];
        //TRAER TODOS LOS PROYECTOS CON EL UD DEL USUARIO
        $proyectos = Proyecto::belongsTo('idUsuario', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }


    //  /crear-proyecto
    public static function crear_proyecto(Router $router){
        //YA ESTA LA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAuth();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //INSTANCIAR EL MODELO PROYECTO
            $proyecto = new Proyecto($_POST);

            //VALIDAR EL FORMULARIO
            $alertas = $proyecto->validarNuevoProyecto();
            
            if (empty($alertas)) {
                //GENERAR LA URL UNICA
                $hash = md5( uniqid() );//32bits
                $proyecto->url = $hash;

                //TOMAR EL ID DEL USUARIO
                $id = $_SESSION['id'];
                $proyecto->idUsuario = $id;

                //GUARDAR
                $proyecto->guardar();
                
                //REDIRECCIONAR
                header('Location: /proyecto?id='.$proyecto->url);
            }
        }

        $alertas = Proyecto::getAlertas();

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    //  /proyecto
    public static function proyecto(Router $router){
        //YA ESTA LA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAuth();

        $url = $_GET['id']; //url del proyecto
        if(!$url) header('Location: /dashboard');

        //REVISAR QUE LA PERSONA QUE VISITA EL PROYECTO ES QUIEN LO CREO
        $proyecto = Proyecto::where('url', $url);
        if ($proyecto->idUsuario !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }



    //  /perfil
    public static function perfil(Router $router){
        //YA ESTA LA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAuth();

        $alertas = [];

        $id = $_SESSION['id'];
        $usuario = Usuario::find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //SINCRONIZAR PARA MANTENER LOS CAMBIOS EN EL OBJETO
            $usuario->sincronizar($_POST);

            //VALIDAR FORMULARIO
            $alertas = $usuario->validar_perfil();

            if (empty($alertas)) {

                //VALIDAR QUE EL EMAIL NUEVO NO EXISTA EN LA BD
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //SI YA ESTA USADO ESE CORREO NUEVO
                    Usuario::setAlerta('error', 'Ese correo ya está vinculado en otra cuenta');
                }else{
                    //SI NO ESTA ESE CORREO

                    //GUARDAR EL USUARIO
                    $usuario->guardar();

                    //CAMBIAR NOMBRE ACTUALIZADO
                    $_SESSION['nombre'] = $usuario->nombre;

                    //MOSTRAR UNA ALERTA
                    Usuario::setAlerta('exito', 'Guardado correctamente');
                }

                
            }
        }

        $alertas = Usuario::getAlertas();
        
        
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    //  /cambiar-password
    public static function cambiar_password(Router $router){

        //YA ESTA LA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //BUSCAR EL USUARIO QUE QUIERE CAMBIAR SU CONTRASEÑA
            $id = $_SESSION['id'];
            $usuario = Usuario::find($id);

            //SINCRONIZAR CON EL OBJETO
            $usuario->sincronizar($_POST);

            //VALIDAR EL FORMULARIO
            $alertas = $usuario->nuevoPassword();

            if (empty($alertas)) {
                //VERIFICAR QUE PASSWORD ACTUAL SEA IGUAL AL HASHEADO
                $resultado = $usuario->comprobarPassword();
                
                if ($resultado) {
                    //SI LA CONTRASEÑA SI ES IGUAL ASIGNAR NUEVO PASSWORD
                    $usuario->password = $usuario->password_nuevo;

                    //ELIMINAR PROPIEDADES DE PASSWORD NUEVO Y ACTUAL
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //HASHEAR PASSWROD NUEVO
                    $usuario->hashPassword();

                    //GUARDAR CAMBIOS EN LA BD
                    $resultado = $usuario->guardar();

                    //MANDAR MENSAJE DE EXITO
                    if ($resultado) {
                        Usuario::setAlerta('exito', 'La contraseña se cambió correctamente');    
                    }else{
                        Usuario::setAlerta('error', 'Hubo ur error al cambiar la contraseña');    
                    }
    
                }else{
                    //SI LA CONTRASEÑA ACRUAL NO ES IGUAL
                    Usuario::setAlerta('error', 'La contraseña actual no es correcta');
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar contraseña',
            'alertas' => $alertas
        ]);
    }
}
