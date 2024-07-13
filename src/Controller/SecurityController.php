<?php
// src/Controller/SecurityController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/deconnexion", name="app_logout", methods={"GET"})
     */
    public function logout(): Response
    {
        // Supprimer le token JWT du localStorage
        $this->removeJwtToken();

        // Redirection vers la page d'accueil ou une autre page après déconnexion
        return $this->redirectToRoute('app_index');
    }

    private function removeJwtToken(): void
    {
        // Supprimer le token JWT du localStorage
        echo "<script>localStorage.removeItem('jwtToken');</script>";
    }
}
