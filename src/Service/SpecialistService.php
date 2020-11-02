<?php


namespace App\Service;

use App\Entity\Specialty;
use App\Entity\Specialist;
use App\Entity\DoctorSpecialty;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class SpecialistService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var FlashBagInterface
     */
    private $bag;

    /**
     * UserSpecialtyService constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     */
    public function __construct(EntityManagerInterface $manager, FlashBagInterface $bag)
    {
        $this->manager = $manager;
        $this->bag = $bag;
    }

    /**
     * @param $specialtyData
     * @param $user
     */
    public function addSpecialty($specialtyData, $user)
    {
        // Add specialty from dropdown
        if ($specialtyData != "") {
            //Check if user does not have that specialty
            $userSpecialties = $this->manager->getRepository(DoctorSpecialty::class)
                ->findByUserIdAndSpecialtyId($user->getId(), $specialtyData);

            if (sizeof($userSpecialties) == 0) {
                $userSpecialty = new DoctorSpecialty();
                $userSpecialty->setFkSpecialist($user);
                $specialty = $this->manager->getRepository(Specialty::class)
                    ->findOneById($specialtyData);
                $userSpecialty->setFkSpecialty($specialty);

                $this->manager->persist($userSpecialty);
                $this->manager->flush();

                $this->bag->add('success', 'Specialybė pridėta.');
            } else {
                $this->bag->add('error', 'Jūs jau esate pasirinkęs šią specialybę.');
            }
        }
//
    }

    public function findMostAppointment(){
        return $this->manager->getRepository(Specialist::class)->findMostAppointment();
    }

    public function findSpecialties($userId){
        return $this->manager->getRepository(DoctorSpecialty::class)
            ->findAllById($userId);
    }

    public function getSpecialist($specialistId){
        return $this->manager->getRepository(Specialist::class)->findById($id);
    }

}
