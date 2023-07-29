<?php

namespace App\Entity;

use App\Repository\SavednoteRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SavednoteRepository::class)]
//#[Groups("savednote:read")]
class Savednote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups("savednote:read")]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    //#[Groups("savednote:read")]
    private ?int $saved = null;

    #[ORM\Column(nullable: true)]
   // #[Groups("savednote:read")]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'savednotes')]
    private ?Recette $recett = null;

    #[ORM\ManyToOne(inversedBy: 'savednotes')]
    private ?Abonnes $abonnees = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSaved(): ?int
    {
        return $this->saved;
    }

    public function setSaved($saved): self
    {
        $this->saved = $saved;

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

    public function getRecett(): ?Recette
    {
        return $this->recett;
    }

    public function setRecett(?Recette $recett): self
    {
        $this->recett = $recett;

        return $this;
    }

    public function getAbonnees(): ?Abonnes
    {
        return $this->abonnees;
    }

    public function setAbonnees(?Abonnes $abonnees): self
    {
        $this->abonnees = $abonnees;

        return $this;
    }
}
