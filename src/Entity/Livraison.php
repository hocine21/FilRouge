<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateLivraison = null;

    #[ORM\Column(length: 255)]
    private ?string $StatutLivraison = null;

    #[ORM\Column(type: Types::BLOB)]
    private $QrCodeLivraison;

    #[ORM\Column(length: 255)]
    private ?string $Rue = null;

    #[ORM\Column(length: 10)]
    private ?string $CodePostal = null;

    #[ORM\Column(length: 255)]
    private ?string $Ville = null;

    #[ORM\OneToMany(targetEntity: CommandeLivraison::class, mappedBy: 'Livraison')]
    private Collection $commandeLivraisons;

    public function __construct()
    {
        $this->commandeLivraisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->DateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $DateLivraison): static
    {
        $this->DateLivraison = $DateLivraison;

        return $this;
    }

    public function getStatutLivraison(): ?string
    {
        return $this->StatutLivraison;
    }

    public function setStatutLivraison(string $StatutLivraison): static
    {
        $this->StatutLivraison = $StatutLivraison;

        return $this;
    }

    public function getQrCodeLivraison()
    {
        return $this->QrCodeLivraison;
    }

    public function setQrCodeLivraison($QrCodeLivraison): static
    {
        $this->QrCodeLivraison = $QrCodeLivraison;

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

    public function getCodePostal(): ?string
    {
        return $this->CodePostal;
    }

    public function setCodePostal(string $CodePostal): static
    {
        $this->CodePostal = $CodePostal;

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

    /**
     * @return Collection<int, CommandeLivraison>
     */
    public function getCommandeLivraisons(): Collection
    {
        return $this->commandeLivraisons;
    }

    public function addCommandeLivraison(CommandeLivraison $commandeLivraison): static
    {
        if (!$this->commandeLivraisons->contains($commandeLivraison)) {
            $this->commandeLivraisons->add($commandeLivraison);
            $commandeLivraison->setLivraison($this);
        }

        return $this;
    }

    public function removeCommandeLivraison(CommandeLivraison $commandeLivraison): static
    {
        if ($this->commandeLivraisons->removeElement($commandeLivraison)) {
            // set the owning side to null (unless already changed)
            if ($commandeLivraison->getLivraison() === $this) {
                $commandeLivraison->setLivraison(null);
            }
        }

        return $this;
    }
}
