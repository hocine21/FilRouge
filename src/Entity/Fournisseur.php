<?php


namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['fournisseur_index', 'fournisseur_detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['fournisseur_index', 'fournisseur_detail'])]
    private ?string $nomFournisseur = null;

    #[ORM\Column(length: 255)]
    #[Groups(['fournisseur_index', 'fournisseur_detail'])]
    private ?string $typeFourniture = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['fournisseur_index', 'fournisseur_detail'])]
    private ?float $prixHTFournisseur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['fournisseur_detail'])]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['fournisseur_detail'])]
    private ?string $telephone = null;

    #[ORM\OneToMany(targetEntity: ProduitFournisseur::class, mappedBy: 'fournisseur', cascade: ['persist', 'remove'])]
    #[Groups(['fournisseur_detail'])]
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
        return $this->nomFournisseur;
    }

    public function setNomFournisseur(string $nomFournisseur): self
    {
        $this->nomFournisseur = $nomFournisseur;
        return $this;
    }

    public function getTypeFourniture(): ?string
    {
        return $this->typeFourniture;
    }

    public function setTypeFourniture(string $typeFourniture): self
    {
        $this->typeFourniture = $typeFourniture;
        return $this;
    }

    public function getPrixHTFournisseur(): ?float
    {
        return $this->prixHTFournisseur;
    }

    public function setPrixHTFournisseur(float $prixHTFournisseur): self
    {
        $this->prixHTFournisseur = $prixHTFournisseur;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
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
            $produitFournisseur->setFournisseur($this);
        }
        return $this;
    }

    public function removeProduitFournisseur(ProduitFournisseur $produitFournisseur): self
    {
        if ($this->produitFournisseurs->removeElement($produitFournisseur)) {
            if ($produitFournisseur->getFournisseur() === $this) {
                $produitFournisseur->setFournisseur(null);
            }
        }
        return $this;
    }
}
