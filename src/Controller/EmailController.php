<?php
// src/Controller/EmailController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * @Route("/send-email", name="send_email")
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hocine.lamamri@imie-paris.fr') // Remplacez par votre adresse e-mail Gmail
            ->to('hocine.lamamri@imie-paris.fr')   // Remplacez par votre adresse e-mail Gmail
            ->subject('Test d\'e-mail avec Gmail')
            ->text('Ceci est un e-mail de test envoyé depuis Symfony avec Gmail.');

        try {
            // Envoi de l'e-mail
            $mailer->send($email);
            return new Response('E-mail envoyé avec succès !');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
        }
    }
}
