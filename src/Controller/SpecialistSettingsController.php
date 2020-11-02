<?php

namespace App\Controller;

use App\Entity\DoctorSpecialty;
use App\Entity\Specialty;
use App\Service\MostAppointmentService;
use App\Service\SpecialistService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SpecialistSettingsController extends AbstractController
{
    /**
     *
     * @Route("/specialist/settings", name="specialist_settings")
     * @param Request $request
     * @param SpecialistService $specialistService
     * @param MostAppointmentService $service
     * @return RedirectResponse|Response
     */
    public function index(Request $request,SpecialistService $specialistService, MostAppointmentService $service)
    {

        if (!$this->getUser()) {
            $this->addFlash('danger', 'Please log in');
            return $this->redirectToRoute('app_login');

        }
        $mostAppointedSpecialist = $service->getMostAppointment();
        $user = $this->getUser();

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



            return $this->redirect($this->generateUrl('specialist_settings'));
        }

        return $this->render('specialist_settings/index.html.twig', [
            'form' => $form->createView(),
            'allSpecialties' => $allSpecialties,
            'mostAppointed' => $mostAppointedSpecialist
        ]);
    }
}
