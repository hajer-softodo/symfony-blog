<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;

class MyEmailController
{
    public $mailer = null;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail()
    {
        $email = (new Email())
            ->from('sender@example.com')
            ->to('recipient@example.com')
            ->subject('Test Email')
            ->text('This is a test email');

        $this->mailer->send($email);
        if($this->mailer->send($email)){
            $response = new Response("votre email est envoyée avec success");
        } else {
            $response = new Response("votre email n'a as été envoyé");
        }
        
        return $response;
    }
}






