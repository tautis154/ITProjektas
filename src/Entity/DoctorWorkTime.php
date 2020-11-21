<?php

namespace App\Entity;

use App\Repository\DoctorWorkTimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorWorkTimeRepository::class)
 */
class DoctorWorkTime
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Specialist::class, inversedBy="doctorWorkTimes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fk_specialist;



    /**
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $day;

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



    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }
}
