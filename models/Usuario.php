<?php

namespace Model;

class Usuario extends ActiveRecord {
    
    
    //BASE DE DATOS igual que en sql
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido',
    'email','password','telefono','admin','confirmado','token'];

    //defino los alias en Usuario $.....
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    //se ordenan y se escribe en caso de que vengan vacios que valor se toma
    public function __construct($args = []) {

        $this->id = $args['id'] ?? null; //id es key
        $this->nombre = $args ['nombre'] ?? '';
        $this->apellido = $args ['apellido'] ?? '';
        $this->email = $args ['email'] ?? '';
        $this->password = $args ['password'] ?? '';
        $this->telefono = $args ['telefono'] ?? '';
        $this->admin = $args ['admin'] ?? '0'; //son enteros en la BD
        $this->confirmado = $args ['confirmado'] ?? '0' ;//son enteros en la BD
        $this->token = $args ['token'] ?? '';

    }

    //mensajes de validacion del formulario
    public function validarNuevaCuenta(){

        //alertas viene de activerecord en funcion getalertas
        if(!$this->nombre) {
            self::$alertas['error'][] = "El nombre es Obligatorio";
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = "El apellido Obligatorio";
        }

        if(!$this->email) {
            self::$alertas['error'][] = "El email es Obligatorio";
        }

        //condiciones para el password, que exista y que tenga cierta cantidad de caracteres
        if(!$this->password) {
            self::$alertas['error'][] = "El email es Obligatorio";
        }

        if(strlen($this->password) < 6 ){
            self::$alertas['error'][] = "El password debe contener al menos 6 letras";

        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email) {
            self::$alertas['error'][] = "el email es obligatorio";
        }
        if(!$this->password) {
            self::$alertas['error'][] = "el password es obligatorio";
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email) {
            self::$alertas['error'][] = "el email es obligatorio";
        }

        return self::$alertas;
    }

    public function validarPassword(){

        if(!$this->password){
            self::$alertas['error'][] = "El password es obligatorio";
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe tener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    //Revisa si el usuario ya existe o no
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email  . "' LIMIT 1 ";


        $resultado = self::$db->query($query);


        if($resultado->num_rows) {
            self::$alertas['error'][] = "El usuario ya esta registrado";
        } 

        return($resultado);

    }

    public function hashPassword() {
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid(); //es como un hash mas corto, para token
    }

    public function comprobarPasswordAndVerificado($password) {
        
        $resultado = password_verify($password,$this->password);

        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = "Password incorrecto o cuenta sin confirmar ";
        } else{
            return true;
        }
    }
}