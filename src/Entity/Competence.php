<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"competence_read"},"enable_max_depth"=true}
 *      },
 *     collectionOperations={
 *          "add_competence"={
 *              "method"="POST",
 *              "path"="admin/competences",
 *              "security"="is_granted('ROLE_CM')",  
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *          },
 *         "show_competence"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/competences"
 *              },
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/competences/{id}",
 *         }, 
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Seul le proprietaite....",
 *              "path"="admin/competences/{id}",
 *         },
 *         "update_competence"={
 *              "method"="PATCH",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/competences/{id}",
 *         },
 *         "update_competence"={
 *              "method"="PUT",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/competences/{id}",
 *         },
 *     },
 * )
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * Groups({"competence_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competence_read"})
     */
    private $isDeleted;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence")
     * @Groups({"competence_read"})
     */
    private $niveau;

    /**
     * @ORM\ManyToMany(targetEntity=Groupecompetence::class, inversedBy="competences")
     */
    private $groupecompetence;

    public function __construct()
    {
        $this->niveau = new ArrayCollection();
        $this->groupecompetence = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getIsDeleted(): ?bool
    {
      return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Groupecompetence[]
     */
    public function getGroupecompetence(): Collection
    {
        return $this->groupecompetence;
    }

    public function addGroupecompetence(Groupecompetence $groupecompetence): self
    {
        if (!$this->groupecompetence->contains($groupecompetence)) {
            $this->groupecompetence[] = $groupecompetence;
        }

        return $this;
    }

    public function removeGroupecompetence(Groupecompetence $groupecompetence): self
    {
        $this->groupecompetence->removeElement($groupecompetence);

        return $this;
    }
}
