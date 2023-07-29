<?php

namespace App\Entity;

use App\Repository\DbaretchefbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: DbaretchefbackRepository::class)]
//#[Groups("dbaretchefback:read")]
#[Vich\Uploadable]
class Dbaretchefback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   // #[Groups("dbaretchefback:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
    #[Assert\NotBlank]
   // #[Groups("dbaretchefback:read")]
    private ?string $type = null;

    #[ORM\Column(length: 255,nullable: true)]
  //  #[Groups("dbaretchefback:read")]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("dbaretchefback:read")]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    //#[Groups("dbaretchefback:read")]
    private ?string $temps_preparation = null;

    #[ORM\Column(nullable: true)]
    //#[Groups("dbaretchefback:read")]
    private ?string $niv_difficulte = null;

    #[ORM\Column(nullable: true)]
    //#[Groups("dbaretchefback:read")]
    private ?int $nombre_ingredient = null;

    #[ORM\ManyToOne(inversedBy: 'dbaretchefbacks')]
    //#[ORM\JoinColumn(nullable: false)]
    private ?Subcategory $subcategory = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("dbaretchefback:read")]
    private ?string $photo = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("dbaretchefback:read")]
    private ?string $video = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: DbartElPrefereBack::class)]
    private Collection $dbartElPrefereBacks;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("dbaretchefback:read")]
    private ?string $ingredients = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("dbaretchefback:read")]
    private ?string $apports_nutritifs = null;

    #[Vich\UploadableField(mapping: "dbaretchefback", fileNameProperty: "photoFile")]
    //#[Assert\File(maxSize: "2M",mimeTypes:"image/jpeg,image/png,image/gif",mimeTypesMessage: "Please upload a valid image (JPEG, PNG, GIF).")]
    #[ORM\Column(length: 255)]
    private ?File $photoFile = null;

    public function __construct()
    {
        $this->dbartElPrefereBacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType( $type): self
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

    public function getNivDifficulte(): ?string
    {
        return $this->niv_difficulte;
    }

    public function setNivDifficulte( $niv_difficulte): self
    {
        $this->niv_difficulte = $niv_difficulte;

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

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

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

    /**
     * @return Collection<int, DbartElPrefereBack>
     */
    public function getDbartElPrefereBacks(): Collection
    {
        return $this->dbartElPrefereBacks;
    }

    public function addDbartElPrefereBack(DbartElPrefereBack $dbartElPrefereBack): self
    {
        if (!$this->dbartElPrefereBacks->contains($dbartElPrefereBack)) {
            $this->dbartElPrefereBacks->add($dbartElPrefereBack);
            $dbartElPrefereBack->setRecipe($this);
        }

        return $this;
    }

    public function removeDbartElPrefereBack(DbartElPrefereBack $dbartElPrefereBack): self
    {
        if ($this->dbartElPrefereBacks->removeElement($dbartElPrefereBack)) {
            // set the owning side to null (unless already changed)
            if ($dbartElPrefereBack->getRecipe() === $this) {
                $dbartElPrefereBack->setRecipe(null);
            }
        }

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

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setPhotoFile(?File $photoFile): void
    {
        $this->photoFile = $photoFile;
        if ($photoFile) {
            // It's necessary to trigger the doctrine event to upload the file when using VichUploaderBundle or other libraries.
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
}
