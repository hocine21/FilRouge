<?php

namespace App\Entity;

use App\Repository\FournisseursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FournisseursRepository::class)]
class Fournisseurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomFournisseur = null;

    #[ORM\Column(length: 255)]
    private ?string $TypeFourniture = null;

    #[ORM\Column]
    private ?float $PrixHTFournisseur = null;

    #[ORM\OneToMany(targetEntity: ProduitFournisseur::class, mappedBy: 'Fournisseur')]
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
        return $this->NomFournisseur;
    }

    public function setNomFournisseur(string $NomFournisseur): static
    {
        $this->NomFournisseur = $NomFournisseur;

        return $this;
    }

    public function getTypeFourniture(): ?string
    {
        return $this->TypeFourniture;
    }

    public function setTypeFourniture(string $TypeFourniture): static
    {
        $this->TypeFourniture = $TypeFourniture;

        return $this;
    }

    public function getPrixHTFournisseur(): ?float
    {
        return $this->PrixHTFournisseur;
    }

    public function setPrixHTFournisseur(float $PrixHTFournisseur): static
    {
        $this->PrixHTFournisseur = $PrixHTFournisseur;

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
