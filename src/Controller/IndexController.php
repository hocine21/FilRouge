<?php
// src/Controller/IndexController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Annotation\IsGranted;

class IndexController extends AbstractController
{
    #[Route('/accueil', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/choix', name: 'app_choix')]
    public function choix(): Response
    {
        return $this->render('choix/choix.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/connexion', name: 'app_connexion_web')]
    public function connexion(): Response
    {
        $form = $this->createForm(\App\Form\ConnexionType::class);
        return $this->render('connexion/connexion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/super-admin', name: 'app_super_admin')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function superAdmin(): Response
    {
        return $this->render('super_admin/super_admin.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/produit', name: 'app_produits')]
    public function produits(): Response
    {
        return $this->render('produit/produit.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/produit-bis', name: 'app_produit_bis')]
    public function produitBis(): Response
    {
        return $this->render('produit/produitBis.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/appro', name: 'app_appro')]
    #[IsGranted('ROLE_APPROVISIONNEMENT')]
    public function appro(): Response
    {
        return $this->render('appro/appro.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/panier', name: 'app_panier')]
    public function panier(): Response
    {
        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/panier/liste-envie', name: 'app_panier_liste_envie')]
    public function listeEnvie(): Response
    {
        return $this->render('panier/liste_envie.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/commercial', name: 'app_commercial')]
    #[IsGranted('ROLE_COMMERCIAL')]
    public function commercial(): Response
    {
        return $this->render('commercial/commercial.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}