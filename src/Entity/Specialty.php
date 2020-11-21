<?php

namespace App\Entity;

use App\Repository\SpecialtyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecialtyRepository::class)
 */
class Specialty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=DoctorSpecialty::class, mappedBy="fk_specialty")
     */
    private $specialtyDoctor;

    public function __construct()
    {
        $this->specialtyDoctor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|DoctorSpecialty[]
     */
    public function getSpecialtyDoctor(): Collection
    {
        return $this->specialtyDoctor;
    }

    public function addSpecialtyDoctor(DoctorSpecialty $specialtyDoctor): self
    {
        if (!$this->specialtyDoctor->contains($specialtyDoctor)) {
            $this->specialtyDoctor[] = $specialtyDoctor;
            $specialtyDoctor->setFkSpecialty($this);
        }

        return $this;
    }

    public function removeSpecialtyDoctor(DoctorSpecialty $specialtyDoctor): self
    {
        if ($this->specialtyDoctor->removeElement($specialtyDoctor)) {
            // set the owning side to null (unless already changed)
            if ($specialtyDoctor->getFkSpecialty() === $this) {
                $specialtyDoctor->setFkSpecialty(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
