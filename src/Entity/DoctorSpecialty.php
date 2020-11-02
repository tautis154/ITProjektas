<?php

namespace App\Entity;

use App\Repository\DoctorSpecialtyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorSpecialtyRepository::class)
 */
class DoctorSpecialty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Specialist::class, inversedBy="doctorSpecialties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_specialist;

    /**
     * @ORM\ManyToOne(targetEntity=Specialty::class, inversedBy="specialtyDoctor")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_specialty;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFkSpecialist(): ?Specialist
    {
        return $this->fk_specialist;
    }

    public function setFkSpecialist(?Specialist $fk_specialist): self
    {
        $this->fk_specialist = $fk_specialist;

        return $this;
    }

    public function getFkSpecialty(): ?Specialty
    {
        return $this->fk_specialty;
    }

    public function setFkSpecialty(?Specialty $fk_specialty): self
    {
        $this->fk_specialty = $fk_specialty;

        return $this;
    }
}
