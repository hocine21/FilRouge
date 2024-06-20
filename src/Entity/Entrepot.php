<?php

namespace App\Entity;

use App\Repository\EntrepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepotRepository::class)]
class Entrepot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Ville = null;

    #[ORM\Column(name: "code_postale")]
    private ?int $codePostale = null;

    #[ORM\Column(length: 255)]
    private ?string $Rue = null;

    #[ORM\OneToMany(targetEntity: EntrepotStock::class, mappedBy: 'Entrepot')]
    private Collection $entrepotStocks;

    public function __construct()
    {
        $this->entrepotStocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): static
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(int $codePostale): static
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->Rue;
    }

    public function setRue(string $Rue): static
    {
        $this->Rue = $Rue;

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
            $entrepotStock->setEntrepot($this);
        }

        return $this;
    }

    public function removeEntrepotStock(EntrepotStock $entrepotStock): static
    {
        if ($this->entrepotStocks->removeElement($entrepotStock)) {
            // set the owning side to null (unless already changed)
            if ($entrepotStock->getEntrepot() === $this) {
                $entrepotStock->setEntrepot(null);
            }
        }

        return $this;
    }
}
