<?php


namespace App\Service;

use App\Entity\Customer;
use App\Entity\DoctorWorkTime;
use App\Entity\Specialty;
use App\Entity\Specialist;
use App\Entity\DoctorSpecialty;
use App\Repository\DoctorWorkTimeRepository;
use Carbon\CarbonInterval;
use DatePeriod;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @param Specialist $specialist
     * @return DoctorWorkTime[]|array|object[]
     */
    public function getSpecialistWorkHours(Specialist $specialist)
    {
        return $this->manager->getRepository(DoctorWorkTime::class)->getWorkHours($specialist);
    }


    /**
     * @param array $workHours
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function getSpecialistHoursFormatted(array $workHours, int $page): array
    {
        $dateArr = [];
        foreach ($workHours as $workHour) {
            $workDay = $workHour->getDay() + (($page - 1) * 7);
            $period = new DatePeriod(
                $workHour->getStartTime(),
                CarbonInterval::minutes(30),
                $workHour->getEndTime()->modify('+1 second')
            );
            $arr = [];
            foreach ($period as $d) {
                $formattedDate = $this->getDateFromDayNumber($workDay);
                $formattedTime = $d->format('H:i');
                if ($this->checkIfDateIsOccupied(
                    new DateTime($formattedDate . $formattedTime),
                    $workHour->getFkSpecialist()->getId(),
                )) {
                    continue;
                } else {
                    $arr[] = $formattedTime;
                }
            }
            $calendar = 'Tvarkarastis';
            $dateArr[] = array(
                'clinicId' => $calendar,
                array(
                    'day' => $workDay,
                    'hours' => $arr,
                ),
            );
        }

        return $dateArr;
    }

    public function getSpecialists( $time, $id){
        $specDates = $this->manager->getRepository(Customer::class)->findByDate($id,$time);
       // var_dump($specDates);
        $newId = array();
        foreach ($specDates as $spec){
            $newId[] = $spec['id'];

           // echo $spec->getFirstName();
            //echo $spec->getAppointedTime();
        }
        $spec = $this->manager->getRepository(Customer::class)->findBy([
            'id' => $newId,
        ]);

        return $spec;

    }

    public function getNextAppointment( $time, $id){
        $specDates = $this->manager->getRepository(Customer::class)->findByGreaterDate($id,$time);

        $newId = array();
        foreach ($specDates as $spec){
            $newId[] = $spec['id'];

            // echo $spec->getFirstName();
            //echo $spec->getAppointedTime();
        }
        $spec = $this->manager->getRepository(Customer::class)->findBy([
            'id' => $newId,
        ]);

        return $spec;

    }

    /**
     * @param DateTime $date
     * @param int $specialistId
     * @return bool
     */
    public function checkIfDateIsOccupied(DateTime $date, int $specialistId)
    {
        return sizeof($this->manager->getRepository(Customer::class)
                ->checkIfWorkHourExists($date, $specialistId)) > 0;
    }

    /**
     * @param $dayCount
     * @return string
     * @throws Exception
     */
    public function getDateFromDayNumber($dayCount): string
    {
        $date = new DateTime();

        if ($this->checkIfDayIsWeekend()) {
            return $date->modify('next week +' . ($dayCount - 1) . ' days')->format('Y-m-d');
        }

        return $date->modify('this week +' . ($dayCount - 1) . ' days')->format('Y-m-d');
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function checkIfDayIsWeekend()
    {
        $date = new DateTime();

        return $date->format('N') >= 6;
    }

    /**
     * @param int $specId
     * @param int $day
     * @param string|null $startOrEndOfDay
     * @return string
     */
    public function getWorkHoursTime(int $specId, int $day, string $startOrEndOfDay = null)
    {
        $spec = $this->manager->getRepository(DoctorWorkTime::class)->findBy([
            'fk_specialist' => $specId,
            'day' => $day,
        ]);
        if (sizeof($spec) == 0) {
            return null;
        }
        if ($startOrEndOfDay == 'start') {
            return $spec[0]->getStartTime()->format('H:i');
        } else {
            return $spec[0]->getEndTime()->format('H:i');
        }
    }

    /**
     * @param int $specId
     * @return mixed
     */
    public function getWorkHours(int $specId)
    {
        return $this->manager->getRepository(DoctorWorkTime::class)->findBy([
            'fk_specialist' => $specId,
        ]);
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
