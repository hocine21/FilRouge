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

    #[ORM\OneToMany(targetEntity: EntrepotBarre::class, mappedBy: 'Entrepot')]
    private Collection $entrepotBarres;

    public function __construct()
    {
        $this->entrepotBarres = new ArrayCollection();
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
     * @return Collection<int, EntrepotBarre>
     */
    public function getEntrepotBarres(): Collection
    {
        return $this->entrepotBarres;
    }

    public function addEntrepotBarre(EntrepotBarre $entrepotBarre): static
    {
        if (!$this->entrepotBarres->contains($entrepotBarre)) {
            $this->entrepotBarres->add($entrepotBarre);
            $entrepotBarre->setEntrepot($this);
        }

        return $this;
    }

    public function removeEntrepotBarre(EntrepotBarre $entrepotBarre): static
    {
        if ($this->entrepotBarres->removeElement($entrepotBarre)) {
            // set the owning side to null (unless already changed)
            if ($entrepotBarre->getEntrepot() === $this) {
                $entrepotBarre->setEntrepot(null);
            }
        }

        return $this;
    }
}
