<?php

class Mailer{
    private $to;
    private $headers;
    private $from = "noreply@inucleus.com";
    private $message;
    private $subject;
    
    function __construct($to, $subject, $fullName, $link = ""){
        $this->setHeaders();
        $this->setReceiver($to);
        $this->setMessage($fullName, $link);
        $this->setSubject($subject);
    }
    
    public function send(){
        mail($this->to, $this->subject, $this->message, $this->headers);
    }
    
    private function setHeaders(){
        $this->headers  = "MIME-Version: 1.0" . "\r\n";
        $this->headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $this->headers .= "From: ".$this->from."\r\n".'Reply-To: '.$this->from."\r\n"."X-Mailer: PHP/" . phpversion();    
    }
    
    private function setReceiver($to){
        $this->to = $to;
    }
    
    private function setMessage($fullName, $link = "www.i-nucleus.com"){
        $this->message = "<html><body>";
        $this->message .= "<h1 style='color:#000;'>Hi ".$fullName."!</h1>";
        $this->message .= "<p style='color:#000;font-size:18px;'>Thank you for signing up with iNucleus. To activate your account please click <a href='".$link."' title='Activate Account'>ACTIVATE ACCOUNT</a></p>";
        $this->message .= "</body></html>";
    }
    
    public function newMessage($link){
        $this->message = "<html><body>";
        $this->message .= "<p style='color:#000;font-size:18px;'>To reset the password click on the link <a href='".$link."' title='Reset Password'>Reset Password</a></p>";
        $this->message .= "</body></html>";
    }
    
    private function setSubject($subject){
        $this->subject = $subject;
    }
}

