<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identityId;

    /**
     * @ORM\ManyToOne(targetEntity=Specialist::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_specialist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getIdentityId(): ?string
    {
        return $this->identityId;
    }

    public function setIdentityId(string $identityId): self
    {
        $this->identityId = $identityId;

        return $this;
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
}
