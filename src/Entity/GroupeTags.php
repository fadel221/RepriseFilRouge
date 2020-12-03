<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagsRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\DataPersister\EntityDataPersister;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ApiResource(
 * 
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"Grptags_read","Grptags_tags_read"},"enable_max_depth"=true}
 *      },
 *     collectionOperations={
 *          "add_groupeTags"={
 *              "path"="admin/groupetags",
 *              "security"="is_granted('ROLE_FORMATEUR')",
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "method"="post"
 *          },
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/groupetags"
 *              },
 * 
 *              "show_groupetags_tags"={
 *              "method"="GET",
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/groupetags/{id}/tags",
 *              "normalization_context"={"groups"={"Grptags_tags_read"},"enable_max_depth"=true}
 *              },
 * 
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/groupetags/{id}",
 *         }, 
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Seul le proprietaite....",
 *              "path"="admin/groupetags/{id}",
 *         },
 *         "updateGroupetag"={
 *              "method"="PATCH",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/groupetags/{id}",
 *         },
 *         "updateGroupeGroupetag"={
 *              "method"="PUT",
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="admin/groupetags/{id}",
 *              "normalization_context"={"groups"={"Grptags_tags_read"},"enable_max_depth"=true}

 *         },
 *     },
 * )
 * @ORM\Entity(repositoryClass=GroupeTagsRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Ce libellé existe déjà"
 *)
 */
class GroupeTags //extends EntityDataPersister
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"Grptags_read","Grptags_tags_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"Grptags_read","Grptags_tags_read"})
     * @Assert\NotBlank()
     * @Assert\Unique
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="groupeTags",cascade={"persist"})
     * @Groups({"Grptags_read","Grptags_tags_read"})
     * @ApiSubresource()
     * 
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"Grptags_read","Grptags_tags_read"})
     */
    private $isDeleted;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->isDeleted=false;
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

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

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
