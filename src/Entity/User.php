<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\DataPersister\UserDataPersister;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "formateur" = "Formateur", "apprenant"="Apprenant","cm"="CM"})
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"user_read",},"enable_max_depth"=true}
 *      },
 * 
 *      collectionOperations={
 *          "add_user"={
 *              "method"="POST",
 *              "path"="admin/users",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas le privilege",
 *              "normalization_context"={"groups"={"user_read"}}
 *          },
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/users",
 *              "defaults"={"id"=null} 
 *          },
 *          "count_user"=
 *           {
 *               "security"="is_granted('ROLE_ADMIN')", 
 *               "security_message"="Vous n'avez pas acces a cette ressource.",
 *               "path"="admin/users/count"
 *           }
 *     },
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"user_read","user_details_read"}},
 *              "path"="admin/users/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *              
 *          },
 *          "get_admin"={
 *              "method"="GET",
 *              "path"="/admins/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="(is_granted('ROLE_FORMATEUR'))",
 *              "security_message"="Vous n'avez pas access à cette Ressource",
 *              "defaults"={"id"=null}
 *          }, 
 *         "delete_user"={
 *              "method"="DELETE",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/users/{id}",
 *              "normalization_context"={"groups"={"user_read","user_details_read"}},
 *              "requirements"={"id"="\d+"},
 *          },
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/users/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/users/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *     },
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 */

class User  implements UserInterface  
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user_read","promo_write","promo_apprenants_write","promo_formateurs_write","promo_groupes_apprenants_read","promo_apprenant_read","groupe_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * 
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_read","apprenant_read","formateur_read","profilsorties_read"})
     * @Assert\NotBlank()
     */
    protected $username;

    
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({"user_read"})
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","user_read","apprenant_read","formateur_read","profilsorties_read","groupe_read","promo_read","promo_apprenant_read","groupe_apprenant_read","promo_formateur_read"})
     *  /**
     *@Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     *)
     * @Assert\Unique
     */
    protected $email;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Groups({"promo_groupes_apprenants_read","user_read","apprenant_read","formateur_read","profilsorties_read","promo_read","promo_apprenant_read","groupe_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * 
     */
    protected $isDeleted;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","user_read","apprenant_read","formateur_read","profilsorties_read","groupe_read","promo_read","promo_apprenant_read","groupe_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_groupes_apprenants_read","user_read","apprenant_read","formateur_read","profilsorties_read","groupe_read","promo_read","promo_apprenant_read","groupe_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     * @Assert\NotBlank()
     */
    protected $nom;


    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"user_read"})
     */
    protected $profil;

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Groups({"user_read"})
     */
    protected $avatar;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo_groupes_apprenants_read","promo_apprenant_read","groupe_apprenant_read","promo_formateur_read","promo_apprenants_read"})
     */
    protected $lastUpdate;

    /**
     * @ORM\OneToMany(targetEntity=Groupecompetence::class, mappedBy="user",cascade={"persist"})
     */
    protected $groupecompetences;

    public function __construct()
    {
        $this->groupecompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

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

    public function getLastupdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastupdate(?\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;
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
            $groupecompetence->setUser($this);
        }

        return $this;
    }

    public function removeGroupecompetence(Groupecompetence $groupecompetence): self
    {
        if ($this->groupecompetences->removeElement($groupecompetence)) {
            // set the owning side to null (unless already changed)
            if ($groupecompetence->getUser() === $this) {
                $groupecompetence->setUser(null);
            }
        }
        return $this;
    }
}
