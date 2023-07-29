<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JeuxRepository::class)]
//#[Groups("jeux:read")]
class Jeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
  //  #[Groups("jeux:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("jeux:read")]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("jeux:read")]
    private ?string $duree = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("jeux:read")]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    //#[Groups("jeux:read")]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    //#[Groups("jeux:read")]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(nullable: true)]
   // #[Groups("jeux:read")]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
   // #[Groups("jeux:read")]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    //#[Groups("jeux:read")]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("jeux:read")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'jeux', targetEntity: Option::class)]
    private Collection $options;


    public function __construct()
    {
        $this->options = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom($nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree($duree): self
    {
        $this->duree = $duree;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getY(): ?Option
    {
        return $this->y;
    }

    public function setY(?Option $y): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setJeux($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getJeux() === $this) {
                $option->setJeux(null);
            }
        }

        return $this;
    }
}
