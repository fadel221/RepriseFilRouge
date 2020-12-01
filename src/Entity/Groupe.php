<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"groupe_read"},"enable_max_depth"=true},
 *          "denormalization_context"={"groups"={"groupe_write"}}
 *      },
 *      collectionOperations={
 *          "post"={
 *              "path"="/admin/groupes",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas le privilege",
 *              "denormalization_context"={"groups"={"groupe_write"}}
 *          },
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/groupes",
 *              "normalization_context"={"groups"={"groupe_read"}}
 *          }
 *          
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"groupe_read"}},
 *              "path"="admin/groupes/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *          },
 * 
 *           
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/groupes/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/groupes/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/groupes/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          "update_user"=
 *          {
 *              "security_post_denormalize"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="groupes/{id}",
 *              "requirements"={"id"="\d+"},
 *              "method"="PUT"
 *          }
 *     },
 * )
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"groupe_write","groupe_read"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"groupe_read"})
     */
    private $isClotured;

    /**
     * @ORM\Column(type="date")
     * @Groups({"groupe_read"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupe",cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"groupe_read","groupe_write"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @Groups({"groupe_read","groupe_write"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups({"groupe_read","groupe_write"})
     */
    private $formateur;

    

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
        $this->setisClotured(false);
        $this->setDateCreation(new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getisClotured(): ?bool
    {
        return $this->isClotured;
    }

    public function setisClotured(bool $isClotured): self
    {
        $this->isClotured = $isClotured;
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

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
        }
        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenant->removeElement($apprenant);
        return $this;
    }
    // Setter pour apprenant
    public function setApprenant ($apprenant)
    {
        $this->apprenant=$apprenant;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) 
        {
            $this->formateur[] = $formateur;
        }
        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateur->removeElement($formateur);
        return $this;
    }
    
}
