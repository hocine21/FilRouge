<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomFournisseur = null;

    #[ORM\Column(length: 255)]
    private ?string $typeFourniture = null;

    #[ORM\Column(type: 'float')]
    private ?float $prixHTFournisseur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\OneToMany(targetEntity: ProduitFournisseur::class, mappedBy: 'fournisseur')]
    private Collection $produitFournisseurs;

    public function __construct()
    {
        $this->produitFournisseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nomFournisseur;
    }

    public function setNomFournisseur(string $nomFournisseur): static
    {
        $this->nomFournisseur = $nomFournisseur;

        return $this;
    }

    public function getTypeFourniture(): ?string
    {
        return $this->typeFourniture;
    }

    public function setTypeFourniture(string $typeFourniture): static
    {
        $this->typeFourniture = $typeFourniture;

        return $this;
    }

    public function getPrixHTFournisseur(): ?float
    {
        return $this->prixHTFournisseur;
    }

    public function setPrixHTFournisseur(float $prixHTFournisseur): static
    {
        $this->prixHTFournisseur = $prixHTFournisseur;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, ProduitFournisseur>
     */
    public function getProduitFournisseurs(): Collection
    {
        return $this->produitFournisseurs;
    }

    public function addProduitFournisseur(ProduitFournisseur $produitFournisseur): static
    {
        if (!$this->produitFournisseurs->contains($produitFournisseur)) {
            $this->produitFournisseurs->add($produitFournisseur);
            $produitFournisseur->setFournisseur($this);
        }

        return $this;
    }

    public function removeProduitFournisseur(ProduitFournisseur $produitFournisseur): static
    {
        if ($this->produitFournisseurs->removeElement($produitFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($produitFournisseur->getFournisseur() === $this) {
                $produitFournisseur->setFournisseur(null);
            }
        }

        return $this;
    }
}
