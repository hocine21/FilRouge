<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $codeQrCommande = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $demandeDevis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatDevis = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $ristourne = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Client $client = null;

    #[ORM\OneToMany(targetEntity: Detail::class, mappedBy: 'commande')]
    private Collection $details;

    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'commande')]
    private Collection $factures;

    #[ORM\OneToMany(targetEntity: CommandeLivraison::class, mappedBy: 'commande')]
    private Collection $commandeLivraisons;

    public function __construct()
    {
        $this->details = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->commandeLivraisons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getCodeQrCommande(): ?string
    {
        return $this->codeQrCommande;
    }

    public function setCodeQrCommande(?string $codeQrCommande): self
    {
        $this->codeQrCommande = $codeQrCommande;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function isDemandeDevis(): ?bool
    {
        return $this->demandeDevis;
    }

    public function setDemandeDevis(bool $demandeDevis): self
    {
        $this->demandeDevis = $demandeDevis;

        return $this;
    }

    public function getEtatDevis(): ?string
    {
        return $this->etatDevis;
    }

    public function setEtatDevis(?string $etatDevis): self
    {
        $this->etatDevis = $etatDevis;

        return $this;
    }

    public function getRistourne(): ?float
    {
        return $this->ristourne;
    }

    public function setRistourne(?float $ristourne): self
    {
        $this->ristourne = $ristourne;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
            $this->details->add($detail);
            $detail->setCommande($this);
        }

        return $this;
    }

    public function removeDetail(Detail $detail): self
    {
        if ($this->details->removeElement($detail)) {
            if ($detail->getCommande() === $this) {
                $detail->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setCommande($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            if ($facture->getCommande() === $this) {
                $facture->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeLivraison>
     */
    public function getCommandeLivraisons(): Collection
    {
        return $this->commandeLivraisons;
    }

    public function addCommandeLivraison(CommandeLivraison $commandeLivraison): self
    {
        if (!$this->commandeLivraisons->contains($commandeLivraison)) {
            $this->commandeLivraisons->add($commandeLivraison);
            $commandeLivraison->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeLivraison(CommandeLivraison $commandeLivraison): self
    {
        if ($this->commandeLivraisons->removeElement($commandeLivraison)) {
            if ($commandeLivraison->getCommande() === $this) {
                $commandeLivraison->setCommande(null);
            }
        }

        return $this;
    }
}
