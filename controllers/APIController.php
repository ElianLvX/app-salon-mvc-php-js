<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicios;
use Model\Servicio;

class APIController {
    public static function index() 
    {
        $servicios = Servicio::all();
        echo json_encode($servicios, JSON_PRETTY_PRINT);
    }

    public static function guardar() 
    {
        // Aqui se guardan las Citas y Los Servicios Seleccionados estos en CitasServicios

        // Almacena la Cita y Devuelve el Id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar(); // Guardar que es de ActiveRecord

        $id = $resultado['id']; // Este es el ID de la Cita Creada hace unos Momentos

        // Almacena los Servicios con el ID de la Cita
        $IdServicios = explode(",",  $_POST['servicios']);
        foreach ($IdServicios as $IdServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $IdServicio
            ];
            // Cada que itere creara un Objeto que contiene (cita_id, servicio_id)
            // y se guardaran es decir si el cliente pidio: Corte de pelo y Corte de Barba
            // Se crearan 2 registros en la tabla CitaServicios
            $citaServicio = new CitaServicios($args); // Aqui se crea la Cita junto con los Servicios Seleccionados
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado], JSON_PRETTY_PRINT);
    }

    public static function eliminar() 
    {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $cita = Cita::find($id); // Busca si en verdad existe un registro con ese ID
            $cita->eliminar(); // Aqui ya se elimina esa cita por si sabemos que existe
            header('Location:' . $_SERVER['HTTP_REFERER']); // Redireccionamos al usuario a la pagina donde anteriormente estaba
            
        }
    }
}