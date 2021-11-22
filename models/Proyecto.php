<?php 

namespace Model;

class Proyecto extends ActiveRecord{
    //TABLA DE LA BD
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'idUsuario', 'proyecto', 'url'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->idUsuario = $args['idUsuario'] ?? '';
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
    }


    //VALIDAR EL FORMULARIO PARA CREAR PROYECTO
    public function validarNuevoProyecto(){
        if (!$this->proyecto) {
            self::$alertas['error'][] = 'El nombre del proyecto es obligatorio';
        }
        
        return self::$alertas;
    }
}


