<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"promo_read"},"enable_max_depth"=true},
 *          "denormalization_context"={"groups"={"promo_write"},"enable_max_depth"=true}
 *      },
 * 
 *  collectionOperations={
 *         "add_promo"={
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos",
 *              "denormalization_context"={"groups"={"promo_write"}},
 *              "normalization_context"={"groups"={"promo_read"},"enable_max_depth"=true},
 *          },
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/promos",
 *              },
 *          "get_apprenant_attente"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/promos/apprenants/attente",
 *              "normalization_context"={"groups"={"promo_apprenant_read"},"enable_max_depth"=true}
 *     },
 * 
 *      "get_formateurs_promo"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/promos/formateurs",
 *              "normalization_context"={"groups"={"promo_formateur_read"},"enable_max_depth"=true}
 *     },
 * 
 *      
 * },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos/{id}",
 *         },
 * 
 *          "groupe_apprenants_on_promo"=
 *          {
 *                  "security"="is_granted('ROLE_ADMIN')", 
 *                  "security_message"="Vous n'avez pas acces a cette ressource.",
 *                  "path"="admin/promos/{idp}/groupes/{idg}/apprenants",
 *                  "method"="get",
 *                  "normalization_context"={"groups"={"promo_groupes_apprenants_read"}}
 *          },
 * 
 *          "promo_id_groupe_principale"={
 *                  "security"="is_granted('ROLE_ADMIN')", 
 *                  "security_message"="Vous n'avez pas acces a cette ressource.",
 *                  "path"="/admin/promos/{id}/principal",
 *                  "method"="get",
 *                  "normalization_context"={"groups"={"promo_read"}}
 *              },
 * 
 *              "promo_id_apprenants_attente"={
 *                  "security"="is_granted('ROLE_ADMIN')", 
 *                  "security_message"="Vous n'avez pas acces a cette ressource.",
 *                  "path"="admin/promos/{id}/apprenants/attente",
 *                  "method"="get",
 *                  "normalization_context"={"groups"={"promo_apprenants_read"}}
 *              },
 * 
 *              "promo_id_formateurs"={
 *                  "security"="is_granted('ROLE_ADMIN')", 
 *                  "security_message"="Vous n'avez pas acces a cette ressource.",
 *                  "path"="admin/promo/{id}/formateurs",
 *                  "method"="get",
 *                  "normalization_context"={"groups"={"promo_formateur_read"}}
 *              },
 * 
 *         
 *          "promo_id_ref"={
 *                  "security"="is_granted('ROLE_ADMIN')", 
 *                  "security_message"="Vous n'avez pas acces a cette ressource.",
 *                  "path"="admin/promos/{id}/referentiels",
 *                  "method"="get",
 *                  "normalization_context"={"groups"={"promo_id_ref"}}
 *              }, 
 *          
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos/{id}",
 *              "denormalization_context"={"groups"={"promo_write"}}
 *         },
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos/{id}",
 *         },
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos/{id}",
 *         },
 * 
 *      "update_formateurs_promo"={
 *              "method"="put",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos/{id}/formateurs",
 *              "denormalization_context"={"groups"={"promo_write"}}
 *              
 * },
 * 
 *    "update_apprenats_promo"={
 *              "method"="put",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos/{id}/apprenants",
 *              "denormalization_context"={"groups"={"promo_write"}}
 * },
 * 
 *    "update_referentiels_promo"={
 *              "method"="put",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/promos/{id}/referentiels",
 *              "denormalization_context"={"groups"={"promo_referentiel_write"}}
 * },
 *     },
 * )
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 * @ApiFilter(SearchFilter::class, properties={"groupe.apprenant.lastUpdate","groupe.type":"exact"})
 * @UniqueEntity(
 *      fields={"titre"},
 *      message="Ce libellé existe déjà"
 *)   
 *
 */

 
class Promo

{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_apprenant_read","groupe_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity=PromoBrief::class, mappedBy="promo")
     * @Groups({"promo_read"})
     */
    private $promoBriefs;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo",cascade={"persist"})
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     *
     */
    private $groupe;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos")
     * @Groups({"promo_read","promo_write","promo_formateur_read"})
     */
    private $formateurs;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $langue;

    /**
     * @ORM\Column(type="text")
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank() 
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date",nullable=true)
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     */
    private $dateDébut;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo_groupes_apprenants_read","promo_read","promo_write","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     */
    private $dateFin;

    /**
     * @ORM\Column(type="blob")
     * @Assert\NotBlank()
     */
    private $avatar;

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Assert\NotBlank()
     */
    private $file;

    /**
     * @Groups({"promo_referentiel_write","promo_groupes_apprenants_read","promo_read","promo_id_ref","promo_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promos")
     * @Assert\NotBlank()
     */
    private $referentiels;

    public function __construct()
    {
        $this->groupe = new ArrayCollection();
        $this->promoBriefs = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->setDateDébut(new DateTime());
        $this->referentiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PromoBrief[]
     */
    public function getPromoBriefs(): Collection
    {
        return $this->promoBriefs;
    }

    public function addPromoBrief(PromoBrief $promoBrief): self
    {
        if (!$this->promoBriefs->contains($promoBrief)) {
            $this->promoBriefs[] = $promoBrief;
            $promoBrief->setPromo($this);
        }

        return $this;
    }

    public function removePromoBrief(PromoBrief $promoBrief): self
    {
        if ($this->promoBriefs->removeElement($promoBrief)) {
            // set the owning side to null (unless already changed)
            if ($promoBrief->getPromo() === $this) {
                $promoBrief->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(?string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;
        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;
        return $this;
    }

    public function getDateDébut(): ?\DateTimeInterface
    {
        return $this->dateDébut;
    }

    public function setDateDébut(\DateTimeInterface $dateDébut): self
    {
        $this->dateDébut = $dateDébut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getAvatar()
    {
        if($this->avatar)
        {
            $avatar_str= stream_get_contents($this->avatar);
            return base64_encode($avatar_str);
        }
        return null;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getFile()
    {
        if($this->file)
        {
            $file_str= stream_get_contents($this->file);
            return base64_encode($file_str);
        }
        return null;
    }

    public function setFile($file): self
    {
        $this->file = $file;
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
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        $this->referentiels->removeElement($referentiel);
        return $this;
    }
}
