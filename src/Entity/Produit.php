<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $NomProduit = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $Image = null;

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $LargeurProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $EpaisseurProduit = null;

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $MasseProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FormeProduit = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $HauteurProduit = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $SectionProduit = null;

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $Marge = null;

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $PrixML = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
#[ORM\JoinColumn(name: 'categorie_id', referencedColumnName: 'id', nullable: false)]
private ?Categorie $Categorie = null;


    #[ORM\OneToMany(targetEntity: Detail::class, mappedBy: 'Produit')]
    private Collection $details;

    #[ORM\OneToMany(targetEntity: Barre::class, mappedBy: 'Produit')]
    private Collection $barres;

    #[ORM\OneToMany(targetEntity: ProduitFournisseur::class, mappedBy: 'Produit')]
    private Collection $produitFournisseurs;

    public function __construct()
    {
        $this->details = new ArrayCollection();
        $this->barres = new ArrayCollection();
        $this->produitFournisseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->NomProduit;
    }

    public function setNomProduit(string $NomProduit): static
    {
        $this->NomProduit = $NomProduit;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    public function getLargeurProduit(): ?float
    {
        return $this->LargeurProduit;
    }

    public function setLargeurProduit(float $LargeurProduit): static
    {
        $this->LargeurProduit = $LargeurProduit;

        return $this;
    }

    public function getEpaisseurProduit(): ?string
    {
        return $this->EpaisseurProduit;
    }

    public function setEpaisseurProduit(?string $EpaisseurProduit): static
    {
        $this->EpaisseurProduit = $EpaisseurProduit;

        return $this;
    }

    public function getMasseProduit(): ?float
    {
        return $this->MasseProduit;
    }

    public function setMasseProduit(?float $MasseProduit): static
    {
        $this->MasseProduit = $MasseProduit;

        return $this;
    }

    public function getFormeProduit(): ?string
    {
        return $this->FormeProduit;
    }

    public function setFormeProduit(?string $FormeProduit): static
    {
        $this->FormeProduit = $FormeProduit;

        return $this;
    }

    public function getHauteurProduit(): ?float
    {
        return $this->HauteurProduit;
    }

    public function setHauteurProduit(?float $HauteurProduit): static
    {
        $this->HauteurProduit = $HauteurProduit;

        return $this;
    }

    public function getSectionProduit(): ?float
    {
        return $this->SectionProduit;
    }

    public function setSectionProduit(?float $SectionProduit): static
    {
        $this->SectionProduit = $SectionProduit;

        return $this;
    }

    public function getMarge(): ?float
    {
        return $this->Marge;
    }

    public function setMarge(?float $Marge): static
    {
        $this->Marge = $Marge;

        return $this;
    }

    public function getPrixML(): ?float
    {
        return $this->PrixML;
    }

    public function setPrixML(float $PrixML): static
    {
        $this->PrixML = $PrixML;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): static
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    /**
     * @return Collection<int, Detail>
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(Detail $detail): static
    {
        if (!$this->details->contains($detail)) {
            $this->details->add($detail);
            $detail->setProduit($this);
        }

        return $this;
    }

    public function removeDetail(Detail $detail): static
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getProduit() === $this) {
                $detail->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Barre>
     */
    public function getBarres(): Collection
    {
        return $this->barres;
    }

    public function addBarre(Barre $barre): static
    {
        if (!$this->barres->contains($barre)) {
            $this->barres->add($barre);
            $barre->setProduit($this);
        }

        return $this;
    }

    public function removeBarre(Barre $barre): static
    {
        if ($this->barres->removeElement($barre)) {
            // set the owning side to null (unless already changed)
            if ($barre->getProduit() === $this) {
                $barre->setProduit(null);
            }
        }

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
            $produitFournisseur->setProduit($this);
        }

        return $this;
    }

    public function removeProduitFournisseur(ProduitFournisseur $produitFournisseur): static
    {
        if ($this->produitFournisseurs->removeElement($produitFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($produitFournisseur->getProduit() === $this) {
                $produitFournisseur->setProduit(null);
            }
        }

        return $this;
    }
}
