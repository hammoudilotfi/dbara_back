<?php

namespace App\Entity;

use App\Repository\AbonnesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AbonnesRepository::class)]
//#[Groups("abonnes:read")]
class Abonnes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups("abonnes:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("abonnes:read")]
    private ?string $num = null;

    #[ORM\Column(nullable: true)]
   // #[Groups("abonnes:read")]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
   // #[Groups("abonnes:read")]
    private ?\DateTimeInterface $date_inscri = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
   // #[Groups("abonnes:read")]
    private ?\DateTimeInterface $date_desinscri = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    //#[Groups("abonnes:read")]
    private ?\DateTimeInterface $lastlogin = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("abonnes:read")]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("abonnes:read")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("abonnes:read")]
    private ?string $photo = null;


    #[ORM\OneToMany(mappedBy: 'abonnes', targetEntity: Vote::class)]
    private Collection $votes;

    #[ORM\OneToMany(mappedBy: 'abonnees', targetEntity: Savednote::class)]
    private Collection $savednotes;

    #[ORM\OneToMany(mappedBy: 'abonnes', targetEntity: Option::class)]
    private Collection $options;

    public function __construct()
    {
        $this->abonnes = new ArrayCollection();
        $this->y = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->savednotes = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getAbonnes(): ArrayCollection
    {
        return $this->abonnes;
    }

    /**
     * @param ArrayCollection $abonnes
     */
    public function setAbonnes(ArrayCollection $abonnes): void
    {
        $this->abonnes = $abonnes;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNum(): ?string
    {
        return $this->num;
    }

    /**
     * @param string|null $num
     */
    public function setNum($num): void
    {
        $this->num = $num;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus( $status): void
    {
        $this->status = $status;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateInscri(): ?\DateTimeInterface
    {
        return $this->date_inscri;
    }

    /**
     * @param \DateTimeInterface|null $date_inscri
     */
    public function setDateInscri(?\DateTimeInterface $date_inscri): void
    {
        $this->date_inscri = $date_inscri;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateDesinscri(): ?\DateTimeInterface
    {
        return $this->date_desinscri;
    }

    /**
     * @param \DateTimeInterface|null $date_desinscri
     */
    public function setDateDesinscri(?\DateTimeInterface $date_desinscri): void
    {
        $this->date_desinscri = $date_desinscri;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    /**
     * @param \DateTimeInterface|null $lastlogin
     */
    public function setLastlogin(?\DateTimeInterface $lastlogin): void
    {
        $this->lastlogin = $lastlogin;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getY(): Collection
    {
        return $this->y;
    }

    public function addY(Option $y): self
    {
        if (!$this->y->contains($y)) {
            $this->y->add($y);
            $y->addOptione($this);
        }

        return $this;
    }

    public function removeY(Option $y): self
    {
        if ($this->y->removeElement($y)) {
            $y->removeOptione($this);
        }

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
            $option->setOptions($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getOptions() === $this) {
                $option->setOptions(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setAbonnes($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getAbonnes() === $this) {
                $vote->setAbonnes(null);
            }
        }

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
            $savednote->setAbonnees($this);
        }

        return $this;
    }

    public function removeSavednote(Savednote $savednote): self
    {
        if ($this->savednotes->removeElement($savednote)) {
            // set the owning side to null (unless already changed)
            if ($savednote->getAbonnees() === $this) {
                $savednote->setAbonnees(null);
            }
        }

        return $this;
    }


}
