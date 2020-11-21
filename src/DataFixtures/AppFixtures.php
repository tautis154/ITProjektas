<?php

namespace App\DataFixtures;

use App\Entity\Day;
use App\Entity\DoctorSpecialty;
use App\Entity\Office;
use App\Entity\Specialist;
use App\Entity\Specialty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $this->loadSpecialties($manager);
        $this->loadOffices($manager);
        $offices = $manager->getRepository(Office::class)->findAll();
        $specialist = new Specialist();
        $specialist->setUsername('tom');

        $specialist->setPassword
        (
            $this->encoder->encodePassword($specialist, '123')
        );

        $specialist->setFirstName('Tadas');
        $specialist->setRoles(['ROLE_USER']);
        $specialist->setFkOffice($offices[0]);
        $specialist->setHowManyAppointed(0);
        $manager->persist($specialist);

        $specialist = new Specialist();
        $specialist->setUsername('linas');

        $specialist->setPassword
        (
            $this->encoder->encodePassword($specialist, '123')
        );

        $specialist->setFirstName('Tom');
        $specialist->setRoles(['ROLE_USER']);
        $specialist->setFkOffice($offices[1]);
        $specialist->setHowManyAppointed(0);
        $manager->persist($specialist);

        $specialist = new Specialist();
        $specialist->setUsername('tautis');

        $specialist->setPassword
        (
            $this->encoder->encodePassword($specialist, '123')
        );

        $specialist->setFirstName('Tautvydas');
        $specialist->setRoles(['ROLE_USER']);
        $specialist->setFkOffice($offices[2]);
        $specialist->setHowManyAppointed(0);
        $manager->persist($specialist);

        $specialist = new Specialist();
        $specialist->setUsername('vilius');

        $specialist->setPassword
        (
            $this->encoder->encodePassword($specialist, '123')
        );

        $specialist->setFirstName('Vilius');
        $specialist->setRoles(['ROLE_USER']);
        $specialist->setFkOffice($offices[3]);
        $specialist->setHowManyAppointed(0);
        $manager->persist($specialist);


        $day = new Day();
        $day->setName('Pirmadienis');
        $manager->persist($day);

        $day = new Day();
        $day->setName('Antradienis');
        $manager->persist($day);

        $day = new Day();
        $day->setName('Treciadienis');
        $manager->persist($day);

        $day = new Day();
        $day->setName('Ketvirtadienis');
        $manager->persist($day);

        $day = new Day();
        $day->setName('Penktadienis');
        $manager->persist($day);


        $manager->flush();



    }

    protected function loadOffices(ObjectManager $manager): void
    {
        $i = 1;
        foreach ($this->getOffices() as $specialty) {
            $spec = new Office();
            $spec->setName($specialty);
            $spec->setStreet("Kilio.g - ". $i);
            $i++;
            $manager->persist($spec);
        }
        $manager->flush();
    }

    protected function loadSpecialties(ObjectManager $manager): void
    {
        foreach ($this->getSpecialties() as $specialty) {
            $spec = new Specialty();
            $spec->setName($specialty);
            $manager->persist($spec);
        }
        $manager->flush();
    }

    protected function getSpecialties(): array
    {
        return [
            'Transporto teise',
            'Darbo teise',
            'Nekilnojamo turto teise',
            'Sutarciu teise',
            'Draudimo teise',
            'Bankroto teise',
            'Finansu teise',
            'Administracine teise'
        ];
    }
    protected function getOffices(): array
    {
        return [
            'Timo-K',
            'Insta',
            'Triple',
            'Next-On-E',
        ];
    }
}
