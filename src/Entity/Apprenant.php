<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"apprenant_read",},"enable_max_depth"=true}
 *      },
 * 
 *      collectionOperations={
 *          "add_apprenant"={
 *              "method"="POST",
 *              "path"="/admin/apprenants",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas le privilege"
 *          },
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/apprenants",
 *              "defaults"={"id"=null}
 *              
 *          },
 *          "get"={
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="/apprenants",
 *              "defaults"={"id"=null}
 *              
 *          },
 *          "add_user"=
 *          {
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "method"="POST", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"apprenant_read","apprenant_details_read"}},
 *              "path"="apprenants/{id}",  
 *          },
 * 
 *          
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"apprenant_read","apprenant_details_read"}},
 *              "path"="admin/apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *              
 *          },
 *           "get"={
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"apprenant_read","apprenant_details_read"}},
 *              "path"="apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *              
 *          },
 * 
 *           
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          "update_user"=
 *          {
 *              "security_post_denormalize"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="apprenants/{id}",
 *              "requirements"={"id"="\d+"},
 *              "method"="PUT"
 *          }
 *     },
 * )
 */
class Apprenant extends User
{
    

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     */
    private $profilSortie;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenant")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=PromoBriefApprenant::class, mappedBy="apprenant")
     */
    private $promoBriefApprenants;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->promoBriefApprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): ?int
    {
         $this->id=$id;
         return $id;
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    /**
     * @return Collection|PromoBriefApprenant[]
     */
    public function getPromoBriefApprenants(): Collection
    {
        return $this->promoBriefApprenants;
    }

    public function addPromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if (!$this->promoBriefApprenants->contains($promoBriefApprenant)) {
            $this->promoBriefApprenants[] = $promoBriefApprenant;
            $promoBriefApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removePromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if ($this->promoBriefApprenants->removeElement($promoBriefApprenant)) {
            // set the owning side to null (unless already changed)
            if ($promoBriefApprenant->getApprenant() === $this) {
                $promoBriefApprenant->setApprenant(null);
            }
        }

        return $this;
    }

}
