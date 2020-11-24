<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"formateur_read"},"enable_max_depth"=true}
 *      },
 * 
 *      collectionOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="/formateurs",
 *              "defaults"={"id"=null}
 *          },
 *          "add_user"=
 *          {
 *              "method"="POST",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="/formateurs",
 *              "defaults"={"id"=null}
 *          },
 *     },   
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"apprenant_read","apprenant_details_read"}},
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *          },
 *          "get"={
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"apprenant_read","apprenant_details_read"}},
 *              "path"="formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *          },
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *     },
 * )
 */
class Formateur extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
