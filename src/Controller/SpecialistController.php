<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Specialist;
use App\Form\RegistrationType;
use App\Service\MostAppointmentService;
use App\Service\SpecialistService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialistController extends AbstractController
{
    /**
     * @Route("/specialist/show/{id}", name="specialist")
     * @param int $id
     * @param Request $request
     * @param SpecialistService $specialistService
     * @param EntityManagerInterface $entityManager
     * @param MostAppointmentService $service
     * @return Response
     */
    public function index(int $id, Request $request, SpecialistService $specialistService,EntityManagerInterface $entityManager, MostAppointmentService $service)
    {
        $mostAppointedSpecialist = $service->getMostAppointment();
        $specialist = $this->getDoctrine()->getRepository(Specialist::class)->findBy([ 'id' => $id]);

        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $responseData = $request->request->get('form');
            $responseData1= $form['firstName']->getData();
            $responseData2= $form['identityNr']->getData();
            $currentAppointed = $specialist[0]->getHowManyAppointed();
            $specialistNew = $specialist[0]->setHowManyAppointed($currentAppointed+1);
            echo $specialist[0]->getHowManyAppointed();
            $customer = new Customer();
            $customer->setFirstName($form['firstName']->getData());
            $customer->setIdentityId($form['identityNr']->getData());
            $customer->setFkSpecialist($specialist[0]);
            $entityManager->persist($customer);
            $entityManager->persist($specialistNew);
            $entityManager->flush();
            $this->addFlash('success', 'You succesfully registered');
            return $this->redirect($this->generateUrl('home'));
        }
        return $this->render('specialist/index.html.twig', [
            'form' => $form->createView(),
            'specialist' => $specialist[0],
            'mostAppointed'=>$mostAppointedSpecialist
            ]);

    }


}
