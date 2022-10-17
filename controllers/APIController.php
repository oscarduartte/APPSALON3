<?php

namespace Controllers;

use Model\Cita;
use Model\Servicio;
use Model\CitaServicio;


class APIController {

    public static function index() {
        $servicios = Servicio::all(); // consulta todos los registros de la base da datos
        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }

    public static function guardar() {

        //almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //almacena los servicios con el id de la cita
        $idServicios = explode( ",",$_POST['servicios'] );

        foreach($idServicios as $idServicio ) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        //retornamos una respuesta
        $respuesta = [
            'resultado' => $resultado
        ];


        echo json_encode($respuesta);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {


            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita ->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']); //Direccionar a la misma pagina de donde estas
            
        }
    }
} 
