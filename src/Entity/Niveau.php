<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NiveauRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ApiResource()
 * @ApiFilter(BooleanFilter::class, properties={"isDeleted"})
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveau")
     * @ORM\JoinColumn(nullable=true)
     */
    private $competence;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competence_read"})
     *  @Assert\NotBlank()
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Groups({"competence_read"})
     *  @Assert\NotBlank()
     */
    private $criterePerformance;

    /**
     * @ORM\Column(type="text")
     * @Groups({"competence_read"})
     *  @Assert\NotBlank()
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $groupeaction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;
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

    public function getCriterePerformance(): ?string
    {
        return $this->criterePerformance;
    }

    public function setCriterePerformance(string $criterePerformance): self
    {
        $this->criterePerformance = $criterePerformance;
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

    public function getGroupeAction(): ?string
    {
        return $this->groupeaction;
    }

    public function setGroupeAction(string $groupeaction): self
    {
        $this->groupeaction = $groupeaction;
        return $this;
    }
}
