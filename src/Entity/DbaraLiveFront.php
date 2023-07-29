<?php

namespace App\Entity;

use App\Repository\DbaraLiveFrontRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DbaraLiveFrontRepository::class)]
//#[Groups("dbaralivefront:read")]
class DbaraLiveFront
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups("dbaralivefront:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
    //#[Groups("dbaralivefront:read")]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
  //  #[Groups("dbaralivefront:read")]
    private ?string $video = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo( $video): self
    {
        $this->video = $video;

        return $this;
    }
}
