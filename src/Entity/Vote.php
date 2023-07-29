<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
//#[Groups("vote:read")]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   // #[Groups("vote:read")]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  //  #[Groups("vote:read")]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(nullable: true)]
   // #[Groups("vote:read")]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?Abonnes $abonnes = null;

    #[ORM\ManyToOne(inversedBy: 'y')]
    private ?Option $optione = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote($note): self
    {
        $this->note = $note;

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

    public function getOptione(): ?Option
    {
        return $this->optione;
    }

    public function setOptione(?Option $optione): self
    {
        $this->optione = $optione;

        return $this;
    }
}
