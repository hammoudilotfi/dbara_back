<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: RecetteRepository::class)]

//#[Groups("recette:read")]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups("recette:read")]
    public ?int $id = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  //  #[Groups("recette:read")]
    public ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(length: 255 ,nullable: true)]
   // #[Groups("recette:read")]
    protected ?string $nom = null;

    #[ORM\Column(length: 255 ,nullable: true)]
   // #[Groups("recette:read")]
    protected ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
   // #[Groups("recette:read")]
    protected ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(nullable: true)]
   // #[Groups("recette:read")]
    protected ?int $temps_preparation = null;

    #[ORM\Column(nullable: true)]
    //#[Groups("recette:read")]
    protected ?int $niv_difficulte = null;

    #[ORM\Column(nullable: true)]
    //#[Groups("recette:read")]
    protected ?int $temperature = null;

    #[ORM\Column(nullable: true)]
   // #[Groups("recette:read")]
    protected ?int $cost = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("recette:read")]
    protected ?string $photo = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("recette:read")]
    protected ?string $video = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    protected ?subcategory $subcategory = null;

    #[ORM\OneToMany(mappedBy: 'recett', targetEntity: Savednote::class)]
    protected Collection $savednotes;

    public function __construct()
    {
        $this->savednotes = new ArrayCollection();
    }


    public function Recette()
    {
        $this->Recette = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
            $this->updated_at =$updated_at;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getTempsPreparation(): ?int
    {
        return $this->temps_preparation;
    }

    public function setTempsPreparation( $temps_preparation): self
    {
        $this->temps_preparation = $temps_preparation;

        return $this;
    }

    public function getNivDifficulte(): ?int
    {
        return $this->niv_difficulte;
    }

    public function setNivDifficulte( $niv_difficulte): self
    {
        $this->niv_difficulte = $niv_difficulte;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature( $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost( $cost): self
    {
        $this->cost = $cost;

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

    public function getSubcategory(): ?subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * @return Collection<int, Savednote>
     */
    public function getSavednotes(): Collection
    {
        return $this->savednotes;
    }

    public function addSavednote(Savednote $savednote): self
    {
        if (!$this->savednotes->contains($savednote)) {
            $this->savednotes->add($savednote);
            $savednote->setRecett($this);
        }

        return $this;
    }

    public function removeSavednote(Savednote $savednote): self
    {
        if ($this->savednotes->removeElement($savednote)) {
            // set the owning side to null (unless already changed)
            if ($savednote->getRecett() === $this) {
                $savednote->setRecett(null);
            }
        }

        return $this;
    }


}
