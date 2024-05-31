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
    private ?\DateTimeInterface $DateCommande = null;

    #[ORM\Column(type: Types::BLOB)]
    private $CodeQrCommande;

    #[ORM\Column(length: 255)]
    private ?string $Etat = null;

    #[ORM\Column]
    private ?bool $DemandeDevis = null;

    #[ORM\Column(length: 255)]
    private ?string $EtatDevis = null;

    #[ORM\Column(nullable: true)]
    private ?float $Ristourne = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Client $Client = null;

    #[ORM\OneToMany(targetEntity: Detail::class, mappedBy: 'Commande')]
    private Collection $details;

    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'Commande')]
    private Collection $factures;

    #[ORM\OneToMany(targetEntity: CommandeLivraison::class, mappedBy: 'Commande')]
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
        return $this->DateCommande;
    }

    public function setDateCommande(\DateTimeInterface $DateCommande): static
    {
        $this->DateCommande = $DateCommande;

        return $this;
    }

    public function getCodeQrCommande()
    {
        return $this->CodeQrCommande;
    }

    public function setCodeQrCommande($CodeQrCommande): static
    {
        $this->CodeQrCommande = $CodeQrCommande;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function isDemandeDevis(): ?bool
    {
        return $this->DemandeDevis;
    }

    public function setDemandeDevis(bool $DemandeDevis): static
    {
        $this->DemandeDevis = $DemandeDevis;

        return $this;
    }

    public function getEtatDevis(): ?string
    {
        return $this->EtatDevis;
    }

    public function setEtatDevis(string $EtatDevis): static
    {
        $this->EtatDevis = $EtatDevis;

        return $this;
    }

    public function getRistourne(): ?float
    {
        return $this->Ristourne;
    }

    public function setRistourne(?float $Ristourne): static
    {
        $this->Ristourne = $Ristourne;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): static
    {
        $this->Client = $Client;

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
            $detail->setCommande($this);
        }

        return $this;
    }

    public function removeDetail(Detail $detail): static
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
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

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setCommande($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
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

    public function addCommandeLivraison(CommandeLivraison $commandeLivraison): static
    {
        if (!$this->commandeLivraisons->contains($commandeLivraison)) {
            $this->commandeLivraisons->add($commandeLivraison);
            $commandeLivraison->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeLivraison(CommandeLivraison $commandeLivraison): static
    {
        if ($this->commandeLivraisons->removeElement($commandeLivraison)) {
            // set the owning side to null (unless already changed)
            if ($commandeLivraison->getCommande() === $this) {
                $commandeLivraison->setCommande(null);
            }
        }

        return $this;
    }
}
