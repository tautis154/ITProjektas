<?php

namespace App\Controller;

use App\Entity\Specialist;
use App\Service\MostAppointmentService;
use App\Service\SpecialistService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialistRegisteredClientsController extends AbstractController
{
    /**
     * @Route("/specialist/registered/clients", name="registered_clients")
     * @param MostAppointmentService $service
     * @param Request $request
     * @param SpecialistService $specialistService
     * @return RedirectResponse|Response
     */
    public function index(MostAppointmentService $service, Request $request, SpecialistService $specialistService)
    {
        $mostAppointedSpecialist = $service->getMostAppointment();
        $user = $this->getUser();
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Please log in');
            return $this->redirectToRoute('app_login');

        }
        $form = $this->createFormBuilder([])
            ->add('selectedTime', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])

            ->add('search', SubmitType::class, ['label' => 'Ieškoti'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $user->getId();
            $saved =  $form['selectedTime']->getData();
            $time =  date_format($saved,'Y-m-d');
            $selectedSpecialists = $specialistService->getSpecialists($time,$id);
          //  echo $selectedSpecialists;
           // echo $time;

            

            return $this->render('specialist_registered_clients/index.html.twig', [
                'customers' => $selectedSpecialists,
                'mostAppointed'=>$mostAppointedSpecialist,
                'form'=>$form->createView()
            ]);
        }


        $clients = $user->getCustomers();

        return $this->render('specialist_registered_clients/index.html.twig', [
            'specialist' => $user,
            'mostAppointed'=>$mostAppointedSpecialist,
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/specialist/registered/clients/search", name="search_specialist")
     * @param Request $request
     * @param MostAppointmentService $service
     * @return Response
     */
    public function results(Request $request,  MostAppointmentService $service)
    {
        $mostAppointedSpecialist = $service->getMostAppointment();

        echo $request->get('name');
        $saved = $request->get('selectedTime');
        $time =  date_format($saved,'Y-m-d');
        echo $time;
       // var_dump($request->get('selectedTime'));



        $form = $this->createFormBuilder([])
            ->add('selectedTime', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])

            ->add('search', SubmitType::class, ['label' => 'Ieškoti'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $saved =  $form['selectedTime']->getData();
           $time =  date_format($saved,'Y-m-d');
            echo $time;

            echo ('wow');
            die();
            return $this->redirectToRoute('search_specialist', $form->getData());
        }



        return $this->render('specialist_registered_clients/search.html.twig', ['mostAppointed'=>$mostAppointedSpecialist,'form'=>$form->createView()]);


    }
    /**
     * @Route("/specialist/registered/clients/next", name="next_client")
     * @param Request $request
     * @param MostAppointmentService $service
     * @return Response
     */
    public function next(Request $request,  MostAppointmentService $service, SpecialistService $specialistService)
    {
        $user = $this->getUser();
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Please log in');
            return $this->redirectToRoute('app_login');

        }
        $user = $this->getUser();
        $id = $user->getId();
        date_default_timezone_set("Europe/Vilnius");
        $currDate = date("Y-m-d h:i:m");
        $nextAppointment = $specialistService->getNextAppointment($currDate,$id);

        $mostAppointedSpecialist = $service->getMostAppointment();
        return $this->render('specialist_registered_clients/nextClient.html.twig', ['currTime' => $currDate,'mostAppointed'=>$mostAppointedSpecialist, 'next_appointment' => $nextAppointment]);


    }
}
