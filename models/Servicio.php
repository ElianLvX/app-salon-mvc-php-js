<?php

namespace Model;

class Servicio extends ActiveRecord{
    // Base de Datps
    protected static $tabla = 'servicios';
    protected static $columnasDB = [
        'id',
        'nombre',
        'precio'
    ];

    // Inicio de Bloque de Constructor
    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
    // Fin de Inicio de Constructor

    public function validar()
    {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }

        if(!$this->precio) {
            self::$alertas['error'][] = 'El precio del servicio es obligatorio';
        }

        if(!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El precio no es válido';
        }

        return self::$alertas;
    }
}