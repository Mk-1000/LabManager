<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ProjectDeRecherche;
use App\Repository\EquipmentRepository;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $etat = null;

    #[ORM\ManyToOne(inversedBy: 'equipments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?ProjectDeRecherche $projectDeRecherche = null;

    #[ORM\ManyToOne(inversedBy: 'equipments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chercheur $chercheur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getProjectDeRecherche(): ?ProjectDeRecherche
    {
        return $this->projectDeRecherche;
    }

    public function setProjectDeRecherche(?ProjectDeRecherche $projectDeRecherche): static
    {
        $this->projectDeRecherche = $projectDeRecherche;

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
    public function useEquipment(ProjectDeRecherche $newProject): bool
    {
        $updated = false; // Initialize the variable to track updates
    
        // If the state is false or null, change it to true
        if ($this->etat === false || $this->etat === null) {
            $this->etat = true;
            $updated = true; // Set as updated
        }
    
        // Change the projectDeRecherche
        $this->projectDeRecherche = $newProject;
    
        // Return the flag indicating if updates were made
        return $updated;
    }
    

}
