<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
//#[Groups("category:read")]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   // #[Groups("category:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("category:read")]
    private ?string $titre = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("category:read")]
    private ?string $description = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("category:read")]
    private ?string $icone = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Subcategory::class)]
    private Collection $subcategories;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre( $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription( $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone( $icone): self
    {
        $this->icone = $icone;

        return $this;
    }

    /**
     * @return Collection<int, Subcategory>
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): self
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories->add($subcategory);
            $subcategory->setCategory($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): self
    {
        if ($this->subcategories->removeElement($subcategory)) {
            // set the owning side to null (unless already changed)
            if ($subcategory->getCategory() === $this) {
                $subcategory->setCategory(null);
            }
        }

        return $this;
    }
}
