<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Quantite = null;

    #[ORM\Column]
    private ?float $Longueur = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    private ?Produit $Produit = null;

    #[ORM\OneToMany(targetEntity: EntrepotStock::class, mappedBy: 'Stock')]
    private Collection $entrepotStocks;

    public function __construct()
    {
        $this->entrepotStocks = new ArrayCollection();
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

    /**
     * @return Collection<int, EntrepotStock>
     */
    public function getEntrepotStocks(): Collection
    {
        return $this->entrepotStocks;
    }

    public function addEntrepotStock(EntrepotStock $entrepotStock): static
    {
        if (!$this->entrepotStocks->contains($entrepotStock)) {
            $this->entrepotStocks->add($entrepotStock);
            $entrepotStock->setStock($this);
        }

        return $this;
    }

    public function removeEntrepotStock(EntrepotStock $entrepotStock): static
    {
        if ($this->entrepotStocks->removeElement($entrepotStock)) {
            // set the owning side to null (unless already changed)
            if ($entrepotStock->getStock() === $this) {
                $entrepotStock->setStock(null);
            }
        }

        return $this;
    }
}
