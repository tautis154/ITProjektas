<?php


namespace App\Service;

use App\Entity\ClinicSpecialists;
use App\Entity\Specialist;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use PhpParser\Node\Expr\Array_;

class MostAppointmentService
{
    private $manager;

    /**
     * SpecialistService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getMostAppointment()
    {
        $specialists = $this->manager->getRepository(Specialist::class)->findAll();
        $mostAppointed = array();
        foreach ($specialists as $specialist){
            $mostAppointed[] = $specialist->getHowManyAppointed();
        }
        $max = max($mostAppointed);
        return $this->manager->getRepository(Specialist::class)->findMostAppointment($max);
        // Check if specialist already belongs to clinic

    }


}
