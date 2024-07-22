<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getProducts(): array
    {
        $response = $this->client->request('GET', 'http://localhost:8080/api/produits');
        $content = $response->getContent();
        return json_decode($content, true);
    }
}
