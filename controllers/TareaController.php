<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;
use MVC\Router;

class TareaController{
    public static function index(Router $router){
        
    }

    public static function crear(Router $router){
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
                'id' => $resultado['id']
            ];

            echo json_encode($respuesta);
        }
    }

    public static function actualizar(Router $router){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            # code...
        }
    }

    public static function eliminar(Router $router){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            # code...
        }
    }
 }
