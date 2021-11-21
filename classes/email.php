<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    //CORREO DE CONFIRMACION DE CUENTA CREADA
    public function enviarConfirmacion(){
        //INSTANCIAR OBJETO DE phpmailer
        $mail = new PHPMailer();

        //CONFIGURAR SMTP (protocolo de envio de emails)
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '6af1d46c425e21';
        $mail->Password = '48839d180320e5';

        //CONFIGURAR EL CONTENIDO DEL EMAIL
        $mail->setFrom('cuentas@uptask.com');//Quien envía el email
        $mail->addAddress('cuentas@uptask.com', 'UpTask.com');//A quien se envía el email
        $mail->Subject = 'Confirma tu cuenta';//Mensaje que aparece en el email

        //HABILITAR HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //DEFINIR EL CONTENIDO DEL EMAIL
        $contenido = '<html>';
        $contenido .= '<p>Hola <strong>' . $this->nombre . '</strong>, has creado tu cuenta en UpTask, sólo debes confirmarla en el siguiente enlace:</p>';
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tú no solicitaste esta cuenta, ignora el mensaje";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        
        //ENVIAR EL EMAIL
        $mail->send();

    }


    // INSTRUCCIONES PARA CAMBIAR EL PASSWORD
    public function enviarInstrucciones(){
        //INSTANCIAR OBJETO DE phpmailer
        $mail = new PHPMailer();

        //CONFIGURAR SMTP (protocolo de envio de emails)
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '6af1d46c425e21';
        $mail->Password = '48839d180320e5';

        //CONFIGURAR EL CONTENIDO DEL EMAIL
        $mail->setFrom('cuentas@appsalon.com');//Quien envía el email
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');//A quien se envía el email
        $mail->Subject = 'Reestablece tu contraseña';//Mensaje que aparece en el email

        //HABILITAR HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //DEFINIR EL CONTENIDO DEL EMAIL
        $contenido = '<html>';
        $contenido .= '<p>Hola ' . $this->nombre . ', has solicitado cambiar tu contraseña, haz click en el siguiente enlace:</p>';
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Reestablecer contraseña</a></p>";
        $contenido .= "<p>Si tú no hiciste la solicitud, ignora el mensaje";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        
        //ENVIAR EL EMAIL
        $mail->send();
    }
}