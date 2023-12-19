<?php

namespace App\Entity;

use App\Repository\ProjectDeRechercheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectDeRechercheRepository::class)]
class ProjectDeRecherche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $etatAvancement = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chercheur $chercheur = null;

    #[ORM\OneToMany(mappedBy: 'projectDeRecherche', targetEntity: publication::class, orphanRemoval: true)]
    private Collection $publications;

    #[ORM\OneToMany(mappedBy: 'projectDeRecherche', targetEntity: equipment::class, orphanRemoval: true)]
    private Collection $equipments;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->equipments = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEtatAvancement(): ?int
    {
        return $this->etatAvancement;
    }

    public function setEtatAvancement(?int $etatAvancement): static
    {
        $this->etatAvancement = $etatAvancement;

        return $this;
    }

    public function getChercheur(): ?Chercheur
    {
        return $this->chercheur;
    }

    public function setChercheur(?Chercheur $chercheur): static
    {
        $this->chercheur = $chercheur;

        return $this;
    }

    /**
     * @return Collection<int, publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(publication $publication): static
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setProjectDeRecherche($this);
        }

        return $this;
    }

    public function removePublication(publication $publication): static
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getProjectDeRecherche() === $this) {
                $publication->setProjectDeRecherche(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(equipment $equipment): static
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments->add($equipment);
            $equipment->setProjectDeRecherche($this);
        }

        return $this;
    }

    public function removeEquipment(equipment $equipment): static
    {
        if ($this->equipments->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getProjectDeRecherche() === $this) {
                $equipment->setProjectDeRecherche(null);
            }
        }

        return $this;
    }
}
