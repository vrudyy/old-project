<?php

class Mailer2{
    
    private $to;
    private $from = 'noreply@i-nucleus.com';
    private $headers;
    private $subject;
    private $message;
    private $style = 'color: black;
    padding: 14px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block; 
    background-color: orange';
    
    public function newClient($contact, $link){
        $this->to = $contact->contactEmail;
        $this->subject = 'Welcome to i-Nucleus';
        $this->setHeaders();
        $this->message = 'Dear '.$contact->contactFirstName.' '.$contact->contactLastName.
                ',<p>A very welcome to i-Nucleus, the leading educational management software.</p>'
                . '<p>To complete your registration, please verify your email account by clicking the button below. </p>'
                . '<p><a style="'.$this->style.'" href="'.$link.'">confirm your email</a></p>'
                . '<p>In case you face any problems, please do get in touch at support@i-nucleus.com</p>'
                . '<p>Best wishes from all of us here at the i-Nucleus Team.</p>';
        $this->send();
    }
    
    public function resetPassword($contact, $link){
        $this->to = $contact->contactEmail;
        $this->subject = 'Resetting your i-Nucleus access';
        $this->setHeaders();
        $this->message = 'Dear '.$contact->contactFirstName.' '.$contact->contactLastName.
                ',<p>Following your recent request to reset your password for your i-Nucleus account, please use the button below.</p>'
                . '<p><a style="'.$this->style.'" href="'.$link.'">Reset your password</a></p>'
                . '<p>In case you face any problems, please do get in touch at support@i-nucleus.com</p>'
                . '<p>Best wishes from all of us here at the i-Nucleus Team.</p>';
        $this->send();
    }
    
    public function newUser($contact, $link){
        $this->to = $contact->contactEmail;
        $this->subject = 'Welcome to i-Nucleus';
        $this->setHeaders();
        $this->message = 'Dear '.$contact->contactFirstName.' '.$contact->contactLastName.
                ',<p>A very welcome to i-Nucleus, the leading educational management software.</p>'
                . '<p>To access your account, please reset your password by clicking the button below.</p>'
                . '<p><a style="'.$this->style.'" href="'.$link.'">Reset your password</a></p>'
                . '<p>Your username is the same as your email.</p>'
                . '<p>In case you face any problems, please do get in touch at support@i-nucleus.com</p>'
                . '<p>Best wishes from all of us here at the i-Nucleus Team.</p>';
        $this->send();
    }
    
    private function setHeaders(){
        $this->headers  = "MIME-Version: 1.0" . "\r\n";
        $this->headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $this->headers .= "From: ".$this->from."\r\n".'Reply-To: '.$this->from."\r\n"."X-Mailer: PHP/" . phpversion();    
    }
    
    public function basicEmail($to, $from, $subject, $message){
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $headers .= "From: ".$from."\r\n".'Reply-To: '.$from."\r\n"."X-Mailer: PHP/" . phpversion();  
        mail($to, $subject, $message, $headers);
    }
    
    private function send(){
        mail($this->to, $this->subject, $this->message, $this->headers);
    }
    
}

