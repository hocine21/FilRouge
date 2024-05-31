<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HolidayController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/holidays/{year}", name="holidays")
     */
    public function index($year): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            "https://calendrier.api.gouv.fr/jours-feries/metropole/$year.json"
        );

        $holidays = $response->toArray();

        return new JsonResponse($holidays);
    }
}
