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

    #[ORM\ManyToOne(targetEntity: Materiau::class)]
    #[ORM\JoinColumn(name: 'materiau_id', referencedColumnName: 'id', nullable: false)]
    private ?Materiau $materiau;

    #[ORM\OneToMany(targetEntity: Detail::class, mappedBy: 'Produit')]
    private Collection $details;

    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'Produit')]
    private Collection $stocks;

    #[ORM\OneToMany(targetEntity: ProduitFournisseur::class, mappedBy: 'Produit')]
    private Collection $produitFournisseurs;

    public function __construct()
    {
        $this->details = new ArrayCollection();
        $this->stocks = new ArrayCollection();
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

    public function setNomProduit(string $NomProduit): self
    {
        $this->NomProduit = $NomProduit;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getLargeurProduit(): ?float
    {
        return $this->LargeurProduit;
    }

    public function setLargeurProduit(float $LargeurProduit): self
    {
        $this->LargeurProduit = $LargeurProduit;

        return $this;
    }

    public function getEpaisseurProduit(): ?string
    {
        return $this->EpaisseurProduit;
    }

    public function setEpaisseurProduit(?string $EpaisseurProduit): self
    {
        $this->EpaisseurProduit = $EpaisseurProduit;

        return $this;
    }

    public function getMasseProduit(): ?float
    {
        return $this->MasseProduit;
    }

    public function setMasseProduit(?float $MasseProduit): self
    {
        $this->MasseProduit = $MasseProduit;

        return $this;
    }

    public function getFormeProduit(): ?string
    {
        return $this->FormeProduit;
    }

    public function setFormeProduit(?string $FormeProduit): self
    {
        $this->FormeProduit = $FormeProduit;

        return $this;
    }

    public function getHauteurProduit(): ?float
    {
        return $this->HauteurProduit;
    }

    public function setHauteurProduit(?float $HauteurProduit): self
    {
        $this->HauteurProduit = $HauteurProduit;

        return $this;
    }

    public function getSectionProduit(): ?float
    {
        return $this->SectionProduit;
    }

    public function setSectionProduit(?float $SectionProduit): self
    {
        $this->SectionProduit = $SectionProduit;

        return $this;
    }

    public function getMarge(): ?float
    {
        return $this->Marge;
    }

    public function setMarge(?float $Marge): self
    {
        $this->Marge = $Marge;

        return $this;
    }

    public function getPrixML(): ?float
    {
        return $this->PrixML;
    }

    public function setPrixML(float $PrixML): self
    {
        $this->PrixML = $PrixML;

        return $this;
    }

    public function getMateriau(): ?Materiau
    {
        return $this->materiau;
    }

    public function setMateriau(?Materiau $materiau): self
    {
        $this->materiau = $materiau;

        return $this;
    }

    /**
     * @return Collection<int, Detail>
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(Detail $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setProduit($this);
        }

        return $this;
    }

    public function removeDetail(Detail $detail): self
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
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setProduit($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduit() === $this) {
                $stock->setProduit(null);
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

    public function addProduitFournisseur(ProduitFournisseur $produitFournisseur): self
    {
        if (!$this->produitFournisseurs->contains($produitFournisseur)) {
            $this->produitFournisseurs[] = $produitFournisseur;
            $produitFournisseur->setProduit($this);
        }

        return $this;
    }

    public function removeProduitFournisseur(ProduitFournisseur $produitFournisseur): self
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
