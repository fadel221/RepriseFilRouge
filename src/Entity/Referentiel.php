<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\DataPersister\EntityDataPersister;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "pagination_items_per_page"=10, 
 *          "normalization_context"={"groups"={"brief_read","referentiel_read","referentiel_groupecompetence_read"}}
 *     },
 * 
 *     collectionOperations={
 *          "add_referentiel"={
 *              "method"="POST",
 *              "path"="/admin/referentiels",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas le privilege",
 *              "normalization_context"={"groups"={"referentiel_read","referentiel_groupecompetence_read"}},
 *              "defaults"={"id"=null}
 *          },
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels",
 *              "defaults"={"id"=null}
 *   
 *          },
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels/{id}/groupecompetences",
 *              "defaults"={"id"=null}
 *   
 *          },
 *           "show_groupecompetence"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels",
 *              "normalization_context"={"groups"={"referentiel_read","referentiel_groupecompetence_read"}},
 *              "defaults"={"id"=null}
 *              },
 *              
 *           "show_referentiel_groupecompetence"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels/groupecompetences",
 *              "normalization_context"={"groups"={"referentiel_groupecompetence_read","referentiel_groupecompetence_read","user_details_read"}},
 *              "defaults"={"id"=null}
 *              },
 * 
 *            "show_referentiel_groupecompetence_competence"=
 *              {
    *              "method"="GET",
    *              "security"="is_granted('ROLE_CM')", 
    *              "security_message"="Vous n'avez pas acces a cette ressource.",
    *              "path"="admin/referentiels/groupecompetences/{idr}/competences/{idg}",
    *              "normalization_context"={"groups"={"referentiel_groupecompetence_read","referentiel_groupecompetence_read","user_details_read"}},
    *              "defaults"={"id"=null}
 *              }
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/referentiels/{id}",
 *              "defaults"={"id"=null}
 *          },
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/referentiels/{id}",
 *          },
 *         "update_referentiel"={
 *              "method"="PATCH",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/referentiels/{id}",
 *          },
 *         "update_referentiel"={
 *              "method"="PUT",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/referentiels/{id}",
 *          },
 *     },
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel //extends EntityDataPersister
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"referentiel_read","referentiel_groupecompetence_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"referentiel_read","referentiel_groupecompetence_read"})
     */
    private $libelle;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel_read","referentiel_groupecompetence_read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel_read","referentiel_groupecompetence_read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel_read","referentiel_groupecompetence_read"})
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel_read","referentiel_groupecompetence_read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\ManyToMany(targetEntity=Groupecompetence::class, inversedBy="referentiels",cascade={"persist"}))
     * @ApiSubresource()
     */
    private $groupecompetences;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="referentiel")
     */
    private $briefs;

    public function __construct()
    {
        $this->groupecompetences = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    /**
     * @return Collection|Groupecompetence[]
     */
    public function getGroupecompetences(): Collection
    {
        return $this->groupecompetences;
    }

    public function addGroupecompetence(Groupecompetence $groupecompetence): self
    {
        if (!$this->groupecompetences->contains($groupecompetence)) {
            $this->groupecompetences[] = $groupecompetence;
        }

        return $this;
    }

    public function removeGroupecompetence(Groupecompetence $groupecompetence): self
    {
        $this->groupecompetences->removeElement($groupecompetence);

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->setReferentiel($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            // set the owning side to null (unless already changed)
            if ($brief->getReferentiel() === $this) {
                $brief->setReferentiel(null);
            }
        }

        return $this;
    }
}
