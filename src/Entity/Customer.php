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
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Specialist::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_specialist;

    /**
     * @ORM\Column(type="datetime")
     */
    private $appointedTime;

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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getAppointedTime(): ?\DateTimeInterface
    {
        return $this->appointedTime;
    }

    public function setAppointedTime(\DateTimeInterface $appointedTime): self
    {
        $this->appointedTime = $appointedTime;

        return $this;
    }
}
