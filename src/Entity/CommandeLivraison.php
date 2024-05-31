<?php

namespace App\Entity;

use App\Repository\CommandeLivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeLivraisonRepository::class)]
class CommandeLivraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandeLivraisons')]
    private ?Livraison $Livraison = null;

    #[ORM\ManyToOne(inversedBy: 'commandeLivraisons')]
    private ?Commande $Commande = null;

    #[ORM\Column]
    private ?float $PrixLivraisonParKm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->Livraison;
    }

    public function setLivraison(?Livraison $Livraison): static
    {
        $this->Livraison = $Livraison;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->Commande;
    }

    public function setCommande(?Commande $Commande): static
    {
        $this->Commande = $Commande;

        return $this;
    }

    public function getPrixLivraisonParKm(): ?float
    {
        return $this->PrixLivraisonParKm;
    }

    public function setPrixLivraisonParKm(float $PrixLivraisonParKm): static
    {
        $this->PrixLivraisonParKm = $PrixLivraisonParKm;

        return $this;
    }
}
