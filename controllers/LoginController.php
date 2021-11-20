<?php 

namespace Controllers;

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            # code...
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear mi cuenta'
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
        $router->render('auth/confirmar', [
            'titulo' => 'Cuenta confirmada'
        ]);
    }
}
