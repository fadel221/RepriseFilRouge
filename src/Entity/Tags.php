<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagsRepository;
use App\DataPersister\TagsDataPersister;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"tags_read",},"enable_max_depth"=true}
 *      },
 * 
 *      collectionOperations={
 *          "post"={
 *              "path"="/admin/tags",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas le privilege"
 *          },
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/tags",
 *              "defaults"={"id"=null}
 *          },
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"tag_read","tag_details_read"}},
 *              "path"="admin/tags/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *          },
 *           
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/tags/{id}",
 *              "normalization_context"={"groups"={"tag_read","tag_details_read"}},
 *              "requirements"={"id"="\d+"},
 *          },
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/tags/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/tags/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *     },
 * 
 * 
 * )
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 */
class Tags //extends TagsDataPersister
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"tags_read","Grptags_tags_read"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tags_read","Grptags_tags_read"})
     * @Assert\NotBlank()
     */
    private $descriptif;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tags_read","Grptags_tags_read"})
     * @Assert\NotBlank()
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"tags_read","Grptags_tags_read"})
     */
    private $isDeleted;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTags::class, mappedBy="tags")
     */
    private $groupeTags;

    

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|GroupeTags[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTags $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTags $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

        return $this;
    }
}
