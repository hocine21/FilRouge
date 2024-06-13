<?php

namespace App\Entity;

use App\Repository\EntrepotStockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepotStockRepository::class)]
class EntrepotStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'entrepotStocks')]
    private ?Entrepot $Entrepot = null;

    #[ORM\ManyToOne(inversedBy: 'entrepotStocks')]
    private ?Stock $Stock = null;

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

    public function getStock(): ?Stock
    {
        return $this->Stock;
    }

    public function setStock(?Stock $Stock): static
    {
        $this->Stock = $Stock;

    }
}
