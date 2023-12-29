<?php

namespace App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChercheurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChercheurRepository::class)]
class Chercheur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 50)]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'chercheur', targetEntity: ProjectDeRecherche::class, orphanRemoval: true)]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, ProjectDeRecherche>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(ProjectDeRecherche $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setChercheur($this);
        }

        return $this;
    }

    public function removeProject(ProjectDeRecherche $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getChercheur() === $this) {
                $project->setChercheur(null);
            }
        }

        return $this;
    }

    public static function chercheurSignInCheck(
        string $enteredEmail,
        string $enteredPassword,
        EntityManagerInterface $entityManager
    ): ?Chercheur {
        $chercheurRepository = $entityManager->getRepository(Chercheur::class);
    
        // Find the chercheur by email
        $chercheur = $chercheurRepository->findOneBy(['email' => $enteredEmail]);
    
        if (!$chercheur) {
            return null; // If chercheur not found, return null
        }
    
        // Verify the password
        if ($enteredPassword == $chercheur->getMotDePasse()) {
            return $chercheur; // Password verified, return the Chercheur entity
        }
    
        return null; // If password doesn't match, return null
    }
    
    

    // public static function findChercheurById(EntityManagerInterface $entityManager, int $id): ?Chercheur
    // {
    //     return $entityManager->getRepository(Chercheur::class)->find($id);
    // }

}
