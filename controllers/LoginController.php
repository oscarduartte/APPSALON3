<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {
        $alertas = [];
        $auth = new Usuario; //para que se autollene el usuario

        if($_SERVER['REQUEST_METHOD']==='POST') {
            $auth = new Usuario($_POST);

            $alertas=$auth->validarLogin();

            if(empty($alertas)) {

                //comprobar que exista el usuario
                $usuario = Usuario::where('email',$auth->email);

                if($usuario) {

                    //verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        
                        if(!isset($_SESSION)) {
                            session_start();
                        };

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //redireccionamiento    
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');                
                        }else {
                            header('Location: /cita');
                        }
                    }
                        
                } else {
                    Usuario::setAlerta('error','Usuario no encontrado');
                }

 
            }


        }

        //mandar las alertas al render
        $alertas=Usuario::getAlertas();

        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth'=>$auth  //para que se autollene el usuario
        ]);

    }

    public static function logout() {

        $_SESSION = [];

        header('location: /');



    }

    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST); //crear el objeto vacio
            $alertas= $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);

                if($usuario && $usuario->confirmado === "1"){
                    
                    //Generar token de un solo uso
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email ->enviarInstrucciones();

                    //alerta de exito
                    Usuario::setAlerta('exito','Revisa tu email');
                   
                }else {
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');

                }
            }

        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/olvide',[
            'alertas' => $alertas
            
        ]);
    }

    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        //buscar usuario por su token
        $usuario = Usuario::where('token', $token);
     

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no valido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){

                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                
                if($resultado) {
                    header('Location: /');
                }

            }

        }


        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error

        ]);
 
    }

    public static function crear(Router $router) {
        $usuario = new Usuario; //esto guarda los valores del formulario para
        //no tener que llenarlo de nuevo vista "crear-cuenta"

        $alertas=[];        //alertas vacias

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);//esto es para que se guarden los datos
            //al dar click y que alguno de ellos no sea valido
            $alertas = $usuario->validarNuevaCuenta();

            //revisar que alertas este vacio para registrar el usuario
            if(empty($alertas)){
                echo "Pasaste la validacion";

                //Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear el password
                    $usuario->hashPassword();
                    
                    //Generar un token unico
                    $usuario->crearToken();

                    //enviar el email a mailtrap
                    $email = new Email($usuario->nombre, $usuario->email,
                    $usuario->token);

                    $email->enviarConfirmacion();
                    
                    //crear usuario $resultado viene de active record
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /mensaje') ; //Redirecciono al usuario
                    }

                    //no esta registrado
                }
                
            }


        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,//esto manda los valores del usuario previamente guardados los deja disponibles en la vista de crear-cuenta
            'alertas'=> $alertas  //esto manda los valores de alerta a vista "crear-cuenta"
        ]);
        
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)) {
            //mostrar mensaje de error
            Usuario::setAlerta('error','Token no valido'); //Set alerta viene de activerecord

        } else { //modificar al usuario confirmado en la columna de bd

            $usuario->confirmado = "1";
            $usuario->token = null; // por seguridad, para que no quede volando en la red
            $usuario->guardar(); //actualiza la bd de el usuario existente
            Usuario::setAlerta('exito','Cuenta Comprobada Correctamente'); //Set alerta viene de activerecord

        }

        //obtener alertas
        $alertas = Usuario::getAlertas();

        //renderizar la vista
        $router -> render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);

    }
}