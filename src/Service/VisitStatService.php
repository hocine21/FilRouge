<?php

namespace App\Service;

use MongoDB\Client;

class VisitStatService
{
    private $collection;

    public function __construct()
    {
        $client = new Client("mongodb://localhost:27017"); // URL de connexion MongoDB
        $this->collection = $client->selectDatabase('Coup_Acier')->selectCollection('visits');
    }

    public function logVisit(array $data)
    {
        // Insère le document avec les données de la visite
        $insertResult = $this->collection->insertOne($data);
        return $insertResult->getInsertedId();
    }

    public function getAllVisits()
    {
        // Récupère tous les documents de la collection
        return $this->collection->find()->toArray();
    }
}
