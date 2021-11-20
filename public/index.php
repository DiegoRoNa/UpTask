<?php 

/*ESTE ARCHIVO SE ENCARGA DE EJECUTAR INTERNAMENTE LOS CONTROLADORES, MODELOS Y VISTAS
A TRAVÉS DEL ROUTER QUE CONTIENE LAS RUTAS DE LA WEB */

//INCLUIR BD, AUTOLOAD, FUNCIONES Y HERLPERS
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
$router = new Router();




// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();