<?php

// src/Entity/PanierItem.php

namespace App\Entity;

use App\Repository\PanierItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PanierItemRepository::class)]
class PanierItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'PanierItems')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Panier::class, inversedBy: 'PanierItems')]
    private Panier $Panier;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive]
    private int $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function setPanier(?Panier $panier): self
    {
        $this->Panier = $panier;

        return $this;
    }
    public function getPanier(): Panier
    {
        return $this->Panier;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
