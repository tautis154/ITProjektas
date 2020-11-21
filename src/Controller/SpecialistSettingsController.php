<?php

namespace App\Controller;

use App\Entity\Day;
use App\Entity\DoctorSpecialty;
use App\Entity\DoctorWorkTime;
use App\Entity\Specialty;
use App\Service\MostAppointmentService;
use App\Service\SpecialistService;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class SpecialistSettingsController extends AbstractController
{
    /**
     *
     * @Route("/specialist/settings", name="specialist_settings")
     * @param Request $request
     * @param SpecialistService $specialistService
     * @param FlashBagInterface $bag
     * @param UrlGeneratorInterface $urlGenerator
     * @param MostAppointmentService $service
     * @return RedirectResponse|Response
     */
    public function index(Request $request,SpecialistService $specialistService,  FlashBagInterface $bag, UrlGeneratorInterface $urlGenerator, MostAppointmentService $service)
    {

        if (!$this->getUser()) {
            $this->addFlash('danger', 'Please log in');
            return $this->redirectToRoute('app_login');

        }
        $mostAppointedSpecialist = $service->getMostAppointment();
        $user = $this->getUser();
        $days = $this->getDoctrine()->getRepository(Day::class)->findAll();
        $dayss =array();
        $workingHours = $specialistService->getSpecialistWorkHours($user);
        foreach ($days as $day){
            $dayss[] = $day->getName();

        }
        $allSpecialties = $specialistService->findSpecialties(
            $user->getId()
        );

        $choices = array();

        $specialties = $this->getDoctrine()->getRepository(Specialty::class)->findAll();

        foreach ($specialties as $specialty) {
            $choices += array($specialty->getName() => $specialty->getId());
        }

        $form = $this->createFormBuilder([])

            ->add('specialties', ChoiceType::class, [
                'label' => 'Specialty',
                'placeholder' => 'Pasirinkti paslauga',
                'choices' => $choices,
                'required' => false,
                'label_attr' => [
                    'class' => 'col-sm-2 control-label',
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Prideti'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $responseData = $request->request->get('form');

            $specialistService->addSpecialty(
                $responseData['specialties'],
                $user
            );
            //if ($timeStart == "" || $timeEnd == "") {
              //  continue;



            return $this->redirect($this->generateUrl('specialist_settings'));
        }
        if (!is_null($request->get('day'))) {
            $manager = $this->getDoctrine()->getManager();


            $workHours = $specialistService->getWorkHours($user->getId());
            foreach ($workHours as $workHour) { //ismetam visus laikus
                $manager->remove($workHour);
            }
            $hadErrors = false;
            foreach ($request->get('day') as $key => $day) {
                $timeStart = DateTime::createFromFormat('H:i', $day['startTime']);
                $timeEnd = DateTime::createFromFormat('H:i', $day['endTime']);

                //praskipinam jeigu nieko neideta, kad nesugeneruotu default laiku
                if ($timeStart == "" || $timeEnd == "") {

                    continue;
                }

                // praskipinam jeigu blogai ivestas laikas
                if (!$timeStart || !$timeEnd || $timeEnd->diff($timeStart)->format('%R') == '+') {
                    $hadErrors = true;
                    continue;
                }

                $workHours = new DoctorWorkTime(); //sudedam naujus laikus
                $workHours->setFkSpecialist($user);
                $workHours->setDay($key);
                $workHours->setStartTime($timeStart);
                $workHours->setEndTime($timeEnd);

                $manager->persist($workHours);
            }
            $manager->flush();
            if ($hadErrors) {
                $bag->add('error', 'Buvo klaidų išsaugant kai kuriuos laikus, bandykite dar kartą');
            } else {
               $bag->add('success', 'Grafikas išsaugotas.');
            }


            return new RedirectResponse($urlGenerator->generate('specialist_settings'));
        }


        return $this->render('specialist_settings/index.html.twig', [
            'form' => $form->createView(),
            'allSpecialties' => $allSpecialties,
            'mostAppointed' => $mostAppointedSpecialist,
            'workDayList' => $dayss,
            'workHours' => $workingHours,
            'userId' => $user->getId()
        ]);
    }
}
