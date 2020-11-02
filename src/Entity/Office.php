<?php

namespace App\Entity;

use App\Repository\OfficeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfficeRepository::class)
 */
class Office
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
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\OneToMany(targetEntity=Specialist::class, mappedBy="fk_office")
     */
    private $specialists;

    public function __construct()
    {
        $this->specialists = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return Collection|Specialist[]
     */
    public function getSpecialists(): Collection
    {
        return $this->specialists;
    }

    public function addSpecialist(Specialist $specialist): self
    {
        if (!$this->specialists->contains($specialist)) {
            $this->specialists[] = $specialist;
            $specialist->setFkOffice($this);
        }

        return $this;
    }

    public function removeSpecialist(Specialist $specialist): self
    {
        if ($this->specialists->removeElement($specialist)) {
            // set the owning side to null (unless already changed)
            if ($specialist->getFkOffice() === $this) {
                $specialist->setFkOffice(null);
            }
        }

        return $this;
    }
}
