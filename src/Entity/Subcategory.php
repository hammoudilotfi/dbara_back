<?php

namespace App\Entity;

use App\Repository\SubcategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: SubcategoryRepository::class)]
//#[Groups("subcategory:read")]
class Subcategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   // #[Groups("subcategory:read")]


    protected ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("subcategory:read")]
    protected ?string $titre = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("subcategory:read")]
    protected ?string $description = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("subcategory:read")]
    protected ?string $icone = null;

    #[ORM\ManyToOne(inversedBy: 'Subcategories')]
    protected ?category $category = null;

    #[ORM\OneToMany(mappedBy: 'subcategory', targetEntity: Recette::class)]
    protected Collection $recettes;

    #[ORM\OneToMany(mappedBy: 'subcategory', targetEntity: Dbaretelchef::class)]
    private Collection $dbaretelchefs;

    #[ORM\OneToMany(mappedBy: 'subcategory', targetEntity: Dbaretchefback::class)]
    private Collection $dbaretchefbacks;



    public function __construct()
    {
        $this->dbaretelchefs = new ArrayCollection();
        $this->reeldbaras = new ArrayCollection();
        $this->dbaralives = new ArrayCollection();
        $this->dbaretchefbacks = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->getTitre();
    }

   // #[Groups]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre($titre): self
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

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->setSubcategory($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getSubcategory() === $this) {
                $recette->setSubcategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dbaretelchef>
     */
    public function getDbaretelchefs(): Collection
    {
        return $this->dbaretelchefs;
    }

    public function addDbaretelchef(Dbaretelchef $dbaretelchef): self
    {
        if (!$this->dbaretelchefs->contains($dbaretelchef)) {
            $this->dbaretelchefs->add($dbaretelchef);
            $dbaretelchef->setSubcategoryId($this);
        }
        return $this;
    }

    public function removeDbaretelchef(Dbaretelchef $dbaretelchef): self
    {
        if ($this->dbaretelchefs->removeElement($dbaretelchef)) {
            // set the owning side to null (unless already changed)
            if ($dbaretelchef->getSubcategoryId() === $this) {
                $dbaretelchef->setSubcategoryId(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Dbaretchefback>
     */
    public function getDbaretchefbacks(): Collection
    {
        return $this->dbaretchefbacks;
    }

    public function addDbaretchefback(Dbaretchefback $dbaretchefback): self
    {
        if (!$this->dbaretchefbacks->contains($dbaretchefback)) {
            $this->dbaretchefbacks->add($dbaretchefback);
            $dbaretchefback->setSubcategory($this);
        }

        return $this;
    }

    public function removeDbaretchefback(Dbaretchefback $dbaretchefback): self
    {
        if ($this->dbaretchefbacks->removeElement($dbaretchefback)) {
            // set the owning side to null (unless already changed)
            if ($dbaretchefback->getSubcategory() === $this) {
                $dbaretchefback->setSubcategory(null);
            }
        }

        return $this;
    }
}
