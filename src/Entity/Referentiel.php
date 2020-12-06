<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\DataPersister\EntityDataPersister;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiResource(
 *  attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "pagination_items_per_page"=10, 
 *          "normalization_context"={"groups"={"referentiel_read"}}
 *     },
 * collectionOperations={
 *          "post"=
 *              {
 *                 "path"="/admin/referentiels",
 *                 "security"="is_granted('ROLE_ADMIN')",
 *                 "security_message"="Vous n'avez pas le privilege",
 *              },
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels",
 *          },
 *          "getM"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels/{id}/groupecompetences",
 *              "method"="GET"
 *          },
 *           "show_groupecompetence"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels",
 *              },
 *           "show_referentiel_groupecompetence"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels/groupecompetences",
 *              "normalization_context"={"groups"={"referentiel_groupecompetence_read"}}
 *              },
 * 
 *              "referentiel_groupecompetence_competence"=
 *              {
 *              "method"="GET",
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/referentiels/{idr}/groupecompetences/{idg}",
 *              "requirements"={"id"="\d+"}
 *              }
 * 
 *              
 *     },
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/referentiels/{id}",   
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
 * 
 *          
 *     },
 * )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Ce libellé existe déjà"
 *)
 *@ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 */
class Referentiel 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promo_referentiel_write","promo_groupes_apprenants_read","referentiel_read","referentiel_groupecompetence_read","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"promo_groupes_apprenants_read","promo_apprenant_read","referentiel_read","referentiel_groupecompetence_read","promo_id_ref","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     
     */
    private $libelle;
    /**
     * @ORM\Column(type="blob")
     * @Groups({"promo_groupes_apprenants_read","promo_apprenant_read","referentiel_read","referentiel_groupecompetence_read","promo_id_ref","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $presentation;

    /**
     * @ORM\Column(type="string",length=255)
     * @Groups({"promo_groupes_apprenants_read","promo_apprenant_read","referentiel_read","referentiel_groupecompetence_read","promo_id_ref","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","promo_apprenant_read","referentiel_read","referentiel_groupecompetence_read","promo_id_ref","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","promo_apprenant_read","referentiel_read","referentiel_groupecompetence_read","promo_id_ref","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $critereEvaluation;

    /**
     * @ORM\ManyToMany(targetEntity=Groupecompetence::class, inversedBy="referentiels",cascade={"persist"}))
     * @ApiSubresource()
     * @Groups({"referentiel_read","referentiel_groupecompetence_read","promo_id_ref"})
     */
    private $groupecompetences;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="referentiel")
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class,mappedBy="referentiel")
     */
    private $promo;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"promo_groupes_apprenants_read","promo_apprenant_read"})
     */
    private $isDeleted;

    public function __construct()
    {
        $this->groupecompetences = new ArrayCollection();
        $this->briefs = new ArrayCollection();
        $this->promo = new ArrayCollection();
        $this->setIsDeleted(false);
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

    public function getPresentation()
    {
        if($this->presentation)
        {
            $presentation_str= stream_get_contents($this->presentation);
            return base64_encode($presentation_str);
        }
        return null;
    }

    public function setPresentation($presentation): self
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

    /**
     * @return Collection|Promo[]
     */
    public function getPromo(): Collection
    {
        return $this->promo;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promo->contains($promo)) {
            $this->promo[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promo->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

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
}
