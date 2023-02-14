<?php 

namespace App\Mail;

use App\Mail\Mail;
include "App/Mail/Mail.php";

class verificationCode extends Mail {

    public function send() : bool {

        $this->mail->setFrom('kameladel339@gmail.com', 'SYSTEM');
        $this->mail->addAddress($this->mailTo);     //Add a recipient
        $this->mail->isHTML(true);                                  //Set email format to HTML
        $this->mail->Subject = $this->subject;
        $this->mail->Body    = $this->body;

        $this->mail->send();
        return true ;
    }
}



?>