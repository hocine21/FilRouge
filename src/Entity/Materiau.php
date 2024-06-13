<?php

namespace App\Entity;

use App\Repository\MateriauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MateriauRepository::class)]
class Materiau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomMateriau = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMateriau(): ?string
    {
        return $this->nomMateriau;
    }

    public function setNomMateriau(string $nomMateriau): static
    {
        $this->nomMateriau = $nomMateriau;

        return $this;
    }
}
