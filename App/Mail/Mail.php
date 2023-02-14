<?php 

namespace App\Mail;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions

abstract class Mail {

    private $host = 'smtp.gmail.com';
    private $username = 'backend363@gmail.com';
    private $password = 'bomyoqqgkbjhoptz';
    private $port = 587;
    private $mailEncryption = 'tls';
    protected $mailTo , $subject , $body;

    protected PHPMailer $mail;

    public function __construct($email , $subject , $body)
    {

        $this->mailTo = $email;
        $this->subject = $subject;
        $this->body = $body;

        $this->mail = new PHPMailer(true);

        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = $this->host;                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = $this->username;                     //SMTP username
        $this->mail->Password   = $this->password;                               //SMTP password
        $this->mail->SMTPSecure = $this->mailEncryption;            //Enable implicit TLS encryption
        $this->mail->Port       = $this->port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    }

    public abstract function send() : bool ;
    
}





?>