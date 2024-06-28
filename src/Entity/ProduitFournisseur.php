<?php

namespace App\Entity;

use App\Repository\ProduitFournisseurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitFournisseurRepository::class)]
class ProduitFournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Fournisseur::class, inversedBy: 'produitFournisseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fournisseur $fournisseur;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'produitFournisseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date;

    #[ORM\Column(length: 255)]
    private ?string $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
