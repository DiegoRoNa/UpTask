<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;
use MVC\Router;

class TareaController{

    //  /api/tareas
    public static function index(){

        //OBTENER PROYECTO
        $idProyecto = $_GET['id'];
        if(!$idProyecto) header('Location: /dashboard');

        //CONSULTAR EL PROYECTO A LA BD
        $proyecto = Proyecto::where('url', $idProyecto);

        //SI NO EXISTE EL PRYECTO O EL USUARIO NO ES EL AUTOR
        if (!$proyecto || $proyecto->idUsuario !== $_SESSION['id']) header('Location: /404');
        
        //OBTENER LAS TAREAS DEL PROYECTO
        $tareas = Tarea::belongsTo('idProyecto', $proyecto->id);
        
        //RESPUESTA AL FRONTEND
        echo json_encode(['tareas' => $tareas]);
    }


    //  /api/tarea
    public static function crear(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //VERIFICAR QUE EL PROYECTO DONDE SE VA A CREAR LA TAREA EXISTE
            $idProyecto = $_POST['idProyecto'];
            $proyecto = Proyecto::where('url', $idProyecto);

            //SI NO EXISTE EL PROYECTO O EL id DEL USUARIO NO ES IGUAL AL DE LA SESION
            if (!$proyecto || $proyecto->idUsuario !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];

                echo json_encode($respuesta);
                return;
            }

            //INSTANCIAR NUEVA TAREA Y GUARDARLA EN LA BD
            $tarea = new Tarea($_POST);
            
            //AGREGAR EL ID DEL PROYECTO
            $tarea->idProyecto = $proyecto->id;

            //GUARDAR EN LA BD
            $resultado = $tarea->guardar();

            //MANDAR MENSAJE DE EXITO
            $respuesta = [
                'tipo' => 'exito',
                'mensaje' => 'Tarea creada correctamente',
                'id' => $resultado['id'],
                'idProyecto' => $proyecto->id
            ];

            echo json_encode($respuesta);
        }
    }


    //  /api/tarea/actualizar
    public static function actualizar(Router $router){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //VALIDAR QUE EL PROYECTO EXISTE
            $proyecto = Proyecto::where('url', $_POST['idProyecto']);
            //SI NO EXISTE EL PROYECTO O EL id DEL USUARIO NO ES IGUAL AL DE LA SESION
            if (!$proyecto || $proyecto->idUsuario !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];

                echo json_encode($respuesta);
                return;
            }

            //INSTANCIAR LA TAREA CON LA NUEVA INFO
            $tarea = new Tarea($_POST);
            //asignarle el id del proyecto al objeto
            $tarea->idProyecto = $proyecto->id;

            //GUARDARMOS EN LA BD
            $resultado = $tarea->guardar();

            //ENVIAMOS RESPUESTA AL FRONTEND
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'idProyecto' => $proyecto->id,
                    'mensaje' => 'Tarea actualizada'
                ];

                echo json_encode(['respuesta' => $respuesta]);
            }

        }
    }


    //  /api/tarea/eliminar
    public static function eliminar(Router $router){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //VALIDAR QUE EL PROYECTO EXISTE
            $proyecto = Proyecto::where('url', $_POST['idProyecto']);
            //SI NO EXISTE EL PROYECTO O EL id DEL USUARIO NO ES IGUAL AL DE LA SESION
            if (!$proyecto || $proyecto->idUsuario !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];

                echo json_encode($respuesta);
                return;
            }

            //INSTANCIAR LA TAREA CON EL POST
            $tarea = new Tarea($_POST);

            //ELIMINAR DE LA BD
            $resultado = $tarea->eliminar();

            if ($resultado) {
                $respuesta = [
                    'resultado' => $resultado,
                    'mensaje' => 'Tarea eliminada',
                    'tipo' => 'exito'
                ];

                echo json_encode($respuesta);
            }
        }
    }
 }
