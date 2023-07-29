<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
//#[Groups("option:read")]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups("option:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("option:read")]
    private ?string $media = null;

    #[ORM\Column(length: 255,nullable: true)]
  //  #[Groups("option:read")]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
   // #[Groups("option:read")]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column]
    //#[Groups("option:read")]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
   // #[Groups("option:read")]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'options')]
    private ?Jeux $jeux = null;

    #[ORM\OneToMany(mappedBy: 'optione', targetEntity: Vote::class)]
    private Collection $y;

    #[ORM\ManyToOne(inversedBy: 'options')]
    private ?Abonnes $abonnes = null;


    public function __construct()
    {
        $this->y = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia($media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText($text): self
    {
        $this->text = $text;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus($status): self
    {
        $this->status = $status;

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

    public function getOptions(): ?Abonnes
    {
        return $this->options;
    }

    public function setOptions(?Abonnes $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getJeux(): ?Jeux
    {
        return $this->jeux;
    }

    public function setJeux(?Jeux $jeux): self
    {
        $this->jeux = $jeux;

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getY(): Collection
    {
        return $this->y;
    }

    public function addY(Vote $y): self
    {
        if (!$this->y->contains($y)) {
            $this->y->add($y);
            $y->setOptione($this);
        }

        return $this;
    }

    public function removeY(Vote $y): self
    {
        if ($this->y->removeElement($y)) {
            // set the owning side to null (unless already changed)
            if ($y->getOptione() === $this) {
                $y->setOptione(null);
            }
        }

        return $this;
    }

    public function getOptiones(): ?Abonnes
    {
        return $this->optiones;
    }

    public function setOptiones(?Abonnes $optiones): self
    {
        $this->optiones = $optiones;

        return $this;
    }

    public function getAbonnes(): ?Abonnes
    {
        return $this->abonnes;
    }

    public function setAbonnes(?Abonnes $abonnes): self
    {
        $this->abonnes = $abonnes;

        return $this;
    }



}
