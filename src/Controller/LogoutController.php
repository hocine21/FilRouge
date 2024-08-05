<?php
// LogoutController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function logout(LogoutHandlerInterface $logoutHandler): Response
    {
        // Le handler de déconnexion est appelé pour nettoyer la session, etc.
        // Vous n'avez pas besoin d'écrire de code ici pour supprimer la session;
        // Symfony gère cela automatiquement avec le LogoutHandlerInterface.

        // Rediriger l'utilisateur vers la page de connexion
        return $this->redirectToRoute('app_connexion_web');
    }
}
