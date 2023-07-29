<?php

namespace App\Entity;

use App\Repository\JeuxfrontRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JeuxfrontRepository::class)]
//#[Groups("jeuxfront:read")]
class Jeuxfront
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups("jeuxfront:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("jeuxfront:read")]
    private ?string $name = null;

    #[ORM\Column(length: 255,nullable: true)]
   // #[Groups("jeuxfront:read")]
    private ?string $photo = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("jeuxfront:read")]
    private ?string $vote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName( $name): self
    {
        $this->name = $name;

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

    public function getVote(): ?string
    {
        return $this->vote;
    }

    public function setVote( $vote): self
    {
        $this->vote = $vote;

        return $this;
    }
}
