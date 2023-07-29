<?php

namespace App\Entity;

use App\Repository\DbaretelchefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DbaretelchefRepository::class)]
//#[Groups("dbaretchef:read")]
#[Vich\Uploadable]
class Dbaretelchef
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   // #[Groups("dbaretchef:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("dbaretchef:read")]
    private ?string $type = null;


    #[ORM\Column(length: 255,nullable: true)]
  //  #[Groups("dbaretchef:read")]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("dbaretchef:read")]
    private ?string $description = null;


    #[ORM\Column(nullable: true)]
   // #[Groups("dbaretchef:read")]
    private ?string $temps_preparation = null;


    #[ORM\Column(length: 255,nullable: true)]
  //  #[Groups("dbaretchef:read")]
    private ?string $photo = null;

    #[ORM\Column(length: 255,nullable: true)]
  //  #[Groups("dbaretchef:read")]
    private ?string $video = null;

    #[ORM\ManyToOne(inversedBy: 'dbaretelchefs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subcategory $subcategory = null;

    #[ORM\Column(nullable: true)]
   // #[Groups("dbaretchef:read")]
    private ?int $nombre_ingredient = null;

    #[ORM\Column(length: 255,nullable: true)]
  //  #[Groups("dbaretchef:read")]
    private ?string $niv_difficulte = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("dbaretchef:read")]
    private ?string $ingredients = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("dbaretchef:read")]
    private ?string $apports_nutritifs = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: DbartiElPrefere::class)]
    private Collection $dbartiElPreferes;

    public function __construct()
    {
        $this->dbartiElPreferes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom( $nom): self
    {
        $this->nom = $nom;

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

    public function getTempsPreparation(): ?string
    {
        return $this->temps_preparation;
    }
    public function setTempsPreparation( $temps_preparation): self
    {
        $this->temps_preparation = $temps_preparation;
        return $this;
    }


    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto( $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo( $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getNombreIngredient(): ?int
    {
        return $this->nombre_ingredient;
    }

    public function setNombreIngredient( $nombre_ingredient): self
    {
        $this->nombre_ingredient = $nombre_ingredient;

        return $this;
    }

    public function getNivDifficulte(): ?string
    {
        return $this->niv_difficulte;
    }

    public function setNivDifficulte( $niv_difficulte): self
    {
        $this->niv_difficulte = $niv_difficulte;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients( $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getApportsNutritifs(): ?string
    {
        return $this->apports_nutritifs;
    }

    public function setApportsNutritifs( $apports_nutritifs): self
    {
        $this->apports_nutritifs = $apports_nutritifs;

        return $this;
    }

    /**
     * @return Collection<int, DbartiElPrefere>
     */
    public function getDbartiElPreferes(): Collection
    {
        return $this->dbartiElPreferes;
    }

    public function addDbartiElPrefere(DbartiElPrefere $dbartiElPrefere): self
    {
        if (!$this->dbartiElPreferes->contains($dbartiElPrefere)) {
            $this->dbartiElPreferes->add($dbartiElPrefere);
            $dbartiElPrefere->setRecipe($this);
        }

        return $this;
    }

    public function removeDbartiElPrefere(DbartiElPrefere $dbartiElPrefere): self
    {
        if ($this->dbartiElPreferes->removeElement($dbartiElPrefere)) {
            // set the owning side to null (unless already changed)
            if ($dbartiElPrefere->getRecipe() === $this) {
                $dbartiElPrefere->setRecipe(null);
            }
        }

        return $this;
    }
}
