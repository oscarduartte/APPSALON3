<?php

namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;


class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email,$nombre,$token)
    {
         $this->email = $email;
         $this->nombre = $nombre;
         $this->token = $token;
        
    }

    public function enviarConfirmacion() {

    //crear el objeto de email

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Port = 2525;
    $mail->Username = '0f90963ab71ced';
    $mail->Password = 'da2063fc2b22c4';

    $mail->setFrom ('cuentas@appsalon.com');
    $mail->addAddress('cuentas@appsalon.com','Appsalon.com');
    $mail->Subject = 'confirma tu cuenta';

    //set html
    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';


    $contenido= "<html>";
    $contenido.= "<p><strong> Hola". $this->nombre ."</strong> Haz creado tu cuenta
    en app salon, solo debes confirmarla dando click en el siguiente link: </p> " ;
    $contenido.="<p>Presiona aqui: <a href='https://obscure-reaches-67628.herokuapp.com/confirmar-cuenta?token=" . $this->token ."' >Confirmar Cuenta</a> </p> ";
    $contenido.= "<p>si no solicitaste este cambio, ignora este mensaje</p>";
    $contenido.= "</html>";


    $mail->Body = $contenido;

    //enviar emial
    $mail->send();
  }

  public function enviarInstrucciones() {
     //crear el objeto de email

     $mail = new PHPMailer();
     $mail->isSMTP();
     $mail->Host = 'smtp.mailtrap.io';
     $mail->SMTPAuth = true;
     $mail->Port = 2525;
     $mail->Username = '0f90963ab71ced';
     $mail->Password = 'da2063fc2b22c4';
 
     $mail->setFrom ('cuentas@appsalon.com');
     $mail->addAddress('cuentas@appsalon.com','Appsalon.com');
     $mail->Subject = 'Reestablece tu password';
 
     //set html
     $mail->isHTML(TRUE);
     $mail->CharSet = 'UTF-8';
 
 
     $contenido= "<html>";
     $contenido.= "<p><strong> Hola". $this->nombre ."</strong> Haz solicitado
     reestablecer tu password, sigue el siguiente enlace para hacerlo </p> " ;
     $contenido.="<p>Presiona aqui: <a href='https://obscure-reaches-67628.herokuapp.com/recuperar?token=" . $this->token ."' >Recuperar Password</a> </p> ";
     $contenido.= "<p>si no solicitaste este cambio, ignora este mensaje</p>";
     $contenido.= "</html>";
 
 
     $mail->Body = $contenido;
 
     //enviar emial
     $mail->send();

  }

}