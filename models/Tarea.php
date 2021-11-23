<?php 

namespace Model;

class Tarea extends ActiveRecord{
    //TABLA DE LA BD
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id', 'idProyecto', 'nombre', 'estado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->idProyecto = $args['idProyecto'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 0;
    }
}