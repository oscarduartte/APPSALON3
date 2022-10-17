<?php

namespace controllers;

use MVC\Router;

class CitaController {
    
    public static function index(Router $router){
        
        debuguear($_SESSION);
        
        isAuth();


        $router->render('cita/index',[
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']

        ]);

    }
}