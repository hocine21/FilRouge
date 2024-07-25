<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\VisitStatService;

class VisitStatController extends AbstractController
{
    private $visitStatService;

    public function __construct(VisitStatService $visitStatService)
    {
        $this->visitStatService = $visitStatService;
    }

    /**
     * @Route("/api/log-visit", name="visit_stat_log", methods={"POST"})
     */
    public function logVisit(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Valider les données reçues
        if (!isset($data['date']) || !isset($data['page'])) {
            return new Response('Invalid input', Response::HTTP_BAD_REQUEST);
        }

        // Ajouter les données de la visite à la base de données
        $id = $this->visitStatService->logVisit([
            'date' => $data['date'],
            'page' => $data['page']
        ]);

        return new Response("Visit logged with ID: $id");
    }
}
