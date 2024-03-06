<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: PanierItem::class, mappedBy: 'panier', cascade: ['persist'])]
    private Collection $panierItems;

    public function __construct()
    {
        $this->panierItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PanierItem>
     */
    public function getPanierItems(): Collection
    {
        return $this->panierItems;
    }

    public function addPanierItem(PanierItem $panierItem): self
    {
        if (!$this->panierItems->contains($panierItem)) {
            $this->panierItems->add($panierItem);
            $panierItem->setPanier($this);
        }

        return $this;
    }

    public function removePanierItem(PanierItem $panierItem): self
    {
        if ($this->panierItems->contains($panierItem)) {
            $this->panierItems->removeElement($panierItem);
            $panierItem->setPanier(null);
        }

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->panierItems as $panierItem) {
            $total += $panierItem->getTotal();
        }

        return $total;
    }
}
