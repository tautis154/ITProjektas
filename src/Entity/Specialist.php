<?php

namespace App\Entity;

use App\Repository\SpecialistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=SpecialistRepository::class)
 */
class Specialist implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="fk_specialist")
     */
    private $customers;

    /**
     * @ORM\OneToMany(targetEntity=DoctorSpecialty::class, mappedBy="fk_specialist")
     */
    private $doctorSpecialties;

    /**
     * @ORM\ManyToOne(targetEntity=Office::class, inversedBy="specialists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_office;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $howManyAppointed;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->doctorSpecialties = new ArrayCollection();
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
        $roles[] = 'ROLE_USER';

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setFkSpecialist($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getFkSpecialist() === $this) {
                $customer->setFkSpecialist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DoctorSpecialty[]
     */
    public function getDoctorSpecialties(): Collection
    {
        return $this->doctorSpecialties;
    }

    public function addDoctorSpecialty(DoctorSpecialty $doctorSpecialty): self
    {
        if (!$this->doctorSpecialties->contains($doctorSpecialty)) {
            $this->doctorSpecialties[] = $doctorSpecialty;
            $doctorSpecialty->setFkSpecialist($this);
        }

        return $this;
    }

    public function removeDoctorSpecialty(DoctorSpecialty $doctorSpecialty): self
    {
        if ($this->doctorSpecialties->removeElement($doctorSpecialty)) {
            // set the owning side to null (unless already changed)
            if ($doctorSpecialty->getFkSpecialist() === $this) {
                $doctorSpecialty->setFkSpecialist(null);
            }
        }

        return $this;
    }

    public function getFkOffice(): ?Office
    {
        return $this->fk_office;
    }

    public function setFkOffice(?Office $fk_office): self
    {
        $this->fk_office = $fk_office;

        return $this;
    }

    public function getHowManyAppointed(): ?int
    {
        return $this->howManyAppointed;
    }

    public function setHowManyAppointed(?int $howManyAppointed): self
    {
        $this->howManyAppointed = $howManyAppointed;

        return $this;
    }
}
