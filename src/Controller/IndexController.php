<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/accueil', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/super-admin', name: 'app_super_admin')]
    #[Security("is_granted('ROLE_SUPER_ADMIN')")]
    public function superAdmin(): Response
    {
        return $this->render('super_admin/super_admin.html.twig', [
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

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(): Response
    {
        return $this->render('connexion/connexion.html.twig', [
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

    #[Route('/appro', name: 'app_appro')]
    #[Security("is_granted('ROLE_APPROVISIONNEMENT')")]
    public function appro(): Response
    {
        return $this->render('appro/appro.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
