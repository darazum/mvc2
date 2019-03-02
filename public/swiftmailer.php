<?php
require_once '../vendor/autoload.php';

try {

// Create the Transport
    $transport = (new Swift_SmtpTransport('	smtp.mail.ru', 995))
        ->setUsername('loftschool.darazum@mail.ru')
        ->setPassword('loftschool123')
        ->setEncryption('tls')
    ;


// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);


// Create a message
    $message = (new Swift_Message('Wonderful Subject'))
        ->setFrom(['loftschool.darazum@mail.ru' => 'loftschool.darazum@mail.ru'])
        ->setTo(['darazum@mail.ru'])
        ->setBody('Here is the message itself')
    ;

// Send the message
    $result = $mailer->send($message);
} catch (Exception $e) {
    var_dump($e->getMessage());
}