<?php

namespace App\Entity;

use App\Repository\BarreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BarreRepository::class)]
class Barre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Quantite = null;

    #[ORM\Column]
    private ?float $Longueur = null;

    #[ORM\ManyToOne(inversedBy: 'barres')]
    private ?Produit $Produit = null;

    #[ORM\OneToMany(targetEntity: EntrepotBarre::class, mappedBy: 'Barre')]
    private Collection $entrepotBarres;

    public function __construct()
    {
        $this->entrepotBarres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): static
    {
        $this->Quantite = $Quantite;

        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->Longueur;
    }

    public function setLongueur(float $Longueur): static
    {
        $this->Longueur = $Longueur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->Produit;
    }

    public function setProduit(?Produit $Produit): static
    {
        $this->Produit = $Produit;

        return $this;
    }
}
