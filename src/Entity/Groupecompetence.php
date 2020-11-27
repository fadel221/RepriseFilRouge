<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupecompetenceRepository;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\DataPersister\EntityDataPersister;

/**
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"Grpcompetence_read"},"enable_max_depth"=true}
 *      },
 *     collectionOperations={
 *          "add_groupecompetence"={
 *              "method"="POST",
 *              "path"="admin/groupecompetences",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "normalization_context"={"groups"={"Grpcompetence_competence_read"},"enable_max_depth"=true}
 *          },
 *         "show_groupecompetence"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/groupecompetences"
 *              },
 *          "show_groupecompetence_competence"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/groupecompetences/competences",
 *              "normalization_context"={"groups"={"Grpcompetence_competence_read"},"enable_max_depth"=true}
 *              },
 *              "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/groupecompetences/{id}/competences",
 *              "normalization_context"={"groups"={"Grpcompetence_competence_read"},"enable_max_depth"=true}
 *              },
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/groupecompetences/{id}",
 *         }, 
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Seul le proprietaite....",
 *              "path"="admin/groupecompetences/{id}",
 *         },
 *         "update_groupecompetence"={
 *              "method"="PUT",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/groupecompetences/{id}",
 *              "normalization_context"={"groups"={"update_Grpcompetence_read"},"enable_max_depth"=true}

 *         },
 *     },
 * 
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 * @ORM\Entity(repositoryClass=GroupecompetenceRepository::class)
 */
class Groupecompetence extends EntityDataPersister
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, mappedBy="groupecompetence",cascade={"persist"})
     * @Groups({"Grpcompetence_read","Grpcompetence_competence_read","update_Grpcompetence_read"})
     * @ApiSubresource()
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"Grpcompetence_read","update_Grpcompetence_read"})
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"Grpcompetence_read","Grpcompetence_competence_read","update_Grpcompetence_read"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank()
     * @Groups({"Grpcompetence_read","Grpcompetence_competence_read","update_Grpcompetence_read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     * @Groups({"Grpcompetence_read","Grpcompetence_competence_read","update_Grpcompetence_read"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"Grpcompetence_read","Grpcompetence_competence_read"})
     * @Assert\NotBlank()
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Grpcompetence_read","Grpcompetence_competence_read","update_Grpcompetence_read"})
     * @Assert\NotBlank()
     */
    private $descriptif;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="groupecompetences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupecompetences")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->dateCreation=new DateTime();
        $this->setisDeleted(false);
        $this->referentiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->addGroupecompetence($this);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->removeElement($competence)) {
            $competence->removeGroupecompetence($this);
        }

        return $this;
    }

    public function getisDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setisDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupecompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGroupecompetence($this);
        }

        return $this;
    }

}
