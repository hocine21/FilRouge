<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\Column]
    private ?int $CodePostale = null;

    #[ORM\Column(length: 255)]
    private ?string $AdresseEmail = null;

    #[ORM\Column]
    private ?int $NumeroTelephone = null;

    #[ORM\Column(length: 255)]
    private ?string $Ville = null;

    #[ORM\Column(length: 255)]
    private ?string $NomRue = null;

    #[ORM\Column(length: 255)]
    private ?string $MotDePasse = null;

    #[ORM\Column(nullable: true)]
    private ?int $Siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $RaisonSociale = null;

    #[ORM\Column(length: 255)]
    private ?string $Roles = null;

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'Client')]
    private Collection $commandes;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'Client')]
    private Collection $reclamations;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->CodePostale;
    }

    public function setCodePostale(int $CodePostale): static
    {
        $this->CodePostale = $CodePostale;

        return $this;
    }

    public function getAdresseEmail(): ?string
    {
        return $this->AdresseEmail;
    }

    public function setAdresseEmail(string $AdresseEmail): static
    {
        $this->AdresseEmail = $AdresseEmail;

        return $this;
    }

    public function getNumeroTelephone(): ?int
    {
        return $this->NumeroTelephone;
    }

    public function setNumeroTelephone(int $NumeroTelephone): static
    {
        $this->NumeroTelephone = $NumeroTelephone;

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

    public function getNomRue(): ?string
    {
        return $this->NomRue;
    }

    public function setNomRue(string $NomRue): static
    {
        $this->NomRue = $NomRue;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->MotDePasse;
    }

    public function setMotDePasse(string $MotDePasse): static
    {
        $this->MotDePasse = $MotDePasse;

        return $this;
    }

    public function getSiret(): ?int
    {
        return $this->Siret;
    }

    public function setSiret(?int $Siret): static
    {
        $this->Siret = $Siret;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->RaisonSociale;
    }

    public function setRaisonSociale(?string $RaisonSociale): static
    {
        $this->RaisonSociale = $RaisonSociale;

        return $this;
    }

    public function getRoles(): array
    {
        return [$this->Roles];
    }

    public function setRoles(string $Roles): static
    {
        $this->Roles = $Roles;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setClient($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getClient() === $this) {
                $reclamation->setClient(null);
            }
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->MotDePasse;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->AdresseEmail;
    }

    public function eraseCredentials(): void
    {
        // Cette méthode est utilisée pour effacer des informations sensibles
        // qui pourraient être stockées dans l'objet, telles que le mot de passe
        // (nous ne stockons pas de mot de passe en clair dans l'objet Client)
    }
}
