<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     */
    private $referentiel;

    /**
     * @ORM\OneToMany(targetEntity=PromoBrief::class, mappedBy="brief")
     */
    private $promoBriefs;

    /**
     * @ORM\OneToMany(targetEntity=PromoBriefApprenant::class, mappedBy="brief")
     */
    private $promoBriefApprenants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $contexte;

    /**
     * @ORM\Column(type="text")
     */
    private $modalitePedagogiques;

    /**
     * @ORM\Column(type="text")
     */
    private $criteresPerformance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    public function __construct()
    {
        $this->promoBriefs = new ArrayCollection();
        $this->promoBriefApprenants = new ArrayCollection();
        $this->setIsDeleted(false);
        $this->setDatecreation(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

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
            $promoBrief->setBrief($this);
        }

        return $this;
    }

    public function removePromoBrief(PromoBrief $promoBrief): self
    {
        if ($this->promoBriefs->removeElement($promoBrief)) {
            // set the owning side to null (unless already changed)
            if ($promoBrief->getBrief() === $this) {
                $promoBrief->setBrief(null);
            }
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
            $promoBriefApprenant->setBrief($this);
        }

        return $this;
    }

    public function removePromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if ($this->promoBriefApprenants->removeElement($promoBriefApprenant)) {
            // set the owning side to null (unless already changed)
            if ($promoBriefApprenant->getBrief() === $this) {
                $promoBriefApprenant->setBrief(null);
            }
        }

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getModalitePedagogiques(): ?string
    {
        return $this->modalitePedagogiques;
    }

    public function setModalitePedagogiques(string $modalitePedagogiques): self
    {
        $this->modalitePedagogiques = $modalitePedagogiques;

        return $this;
    }

    public function getCriteresPerformance(): ?string
    {
        return $this->criteresPerformance;
    }

    public function setCriteresPerformance(string $criteresPerformance): self
    {
        $this->criteresPerformance = $criteresPerformance;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
