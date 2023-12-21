<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ProjectDeRecherche;
use App\Repository\PublicationRepository;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titre = null;

    #[ORM\Column(length: 50)]
    private ?string $auteurs = null;

    #[ORM\Column(length: 50)]
    private ?string $motsCles = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectDeRecherche $projectDeRecherche = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteurs(): ?string
    {
        return $this->auteurs;
    }

    public function setAuteurs(string $auteurs): static
    {
        $this->auteurs = $auteurs;

        return $this;
    }

    public function getMotsCles(): ?string
    {
        return $this->motsCles;
    }

    public function setMotsCles(string $motsCles): static
    {
        $this->motsCles = $motsCles;

        return $this;
    }

    public function getProjectDeRecherche(): ?ProjectDeRecherche
    {
        return $this->projectDeRecherche;
    }

    public function setProjectDeRecherche(?ProjectDeRecherche $projectDeRecherche): static
    {
        $this->projectDeRecherche = $projectDeRecherche->get;

        return $this;
    }
}
