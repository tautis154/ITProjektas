<?php

namespace App\Controller;

use App\Service\MostAppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DUKController extends AbstractController
{
    /**
     * @Route("/duk", name="duk")
     * @param MostAppointmentService $service
     * @return Response
     */
    public function index(MostAppointmentService $service)
    {
        $mostAppointedSpecialist = $service->getMostAppointment();
        return $this->render('duk/index.html.twig', [
            'controller_name' => 'DUKController',
            'mostAppointed' => $mostAppointedSpecialist
        ]);
    }
}
