<?php

namespace App\Entity;

use App\Repository\EntrepotBarreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepotBarreRepository::class)]
class EntrepotBarre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entrepotBarres')]
    private ?Entrepot $Entrepot = null;

    #[ORM\ManyToOne(inversedBy: 'entrepotBarres')]
    private ?Barre $Barre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntrepot(): ?Entrepot
    {
        return $this->Entrepot;
    }

    public function setEntrepot(?Entrepot $Entrepot): static
    {
        $this->Entrepot = $Entrepot;

        return $this;
    }

    public function getBarre(): ?Barre
    {
        return $this->Barre;
    }

    public function setBarre(?Barre $Barre): static
    {
        $this->Barre = $Barre;

        return $this;
    }
}
