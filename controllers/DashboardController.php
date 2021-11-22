<?php 

namespace Controllers;

use Model\Proyecto;
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

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}
