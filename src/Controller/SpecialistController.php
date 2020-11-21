<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Specialist;
use App\Form\RegistrationType;
use App\Service\MostAppointmentService;
use App\Service\SpecialistService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SpecialistController extends AbstractController
{


    /**
     * @Route ("specialist/register_visit/{specialistId}", name="specialist_register_visit")
     * @param int $specialistId
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @param FlashBagInterface $bag
     * @param SpecialistService $specialistService
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     * @throws Exception
     */
    public function registerVisit(
        int $specialistId,
        Request $request,
        UrlGeneratorInterface $urlGenerator,
        FlashBagInterface $bag,
        SpecialistService $specialistService,
        EntityManagerInterface $entityManager

    ) {

            $manager = $this->getDoctrine()->getManager();
            $reqInfo = explode(';', $request->get('reg_time'));
            $specialist = $this->getDoctrine()->getRepository(Specialist::class)->findBy(['id' => $specialistId]);
            $fullDate = new DateTime($reqInfo[1].$reqInfo[2]);
            // pries idedant pachekinam ar netycia kazkas anksciau jau nebus ten pat uzsiregistraves
            // kolkas redirectinam atgal, poto galbut kazkokia zinute prideti

            if ($specialistService->checkIfDateIsOccupied(
                $fullDate,
                $specialist[0]->getId(),
            )) {
                return new RedirectResponse($urlGenerator->generate(
                    'specialist',
                    ['id' => $specialist[0]->getId()]
                ));
            }

            $currentAppointed = $specialist[0]->getHowManyAppointed();
            $specialistNew = $specialist[0]->setHowManyAppointed($currentAppointed+1);
           // echo $specialist[0]->getHowManyAppointed();
            $customer = new Customer();
            echo $request->get('registration_firstName');

            $customer->setFirstName($request->get('registration_firstName'));
            $customer->setLastName($request->get('registration_lastName'));
            $customer->setMessage($request->get('registration_message'));
            $customer->setFkSpecialist($specialist[0]);
             $customer->setAppointedTime($fullDate);
            $entityManager->persist($customer);
            $entityManager->persist($specialistNew);




            $manager->flush();

            $bag->add('success', 'Užregistruota sėkmingai');

            return new RedirectResponse($urlGenerator->generate('specialist', ['id' => $specialist[0]->getId()]));

    }

    /**
     * @Route("/specialist/show/{id}", name="specialist")
     * @param int $id
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param SpecialistService $specialistService
     * @param EntityManagerInterface $entityManager
     * @param MostAppointmentService $service
     * @return Response
     */
    public function index(int $id, Request $request, PaginatorInterface $paginator, SpecialistService $specialistService,EntityManagerInterface $entityManager, MostAppointmentService $service)
    {
        $mostAppointedSpecialist = $service->getMostAppointment();
        $specialist = $this->getDoctrine()->getRepository(Specialist::class)->findBy([ 'id' => $id]);
        $workHours = $specialistService->getSpecialistWorkHours($specialist[0]);
        $page = $request->query->getInt('page', 1);

        $queryBuilder = $specialistService->getSpecialistHoursFormatted($workHours, $page);



        return $this->render('specialist/index.html.twig', [

            'specialist' => $specialist[0],
            'workHours' => $queryBuilder,
            'id' => $id,
            'page' => $page,
            'mostAppointed'=>$mostAppointedSpecialist
            ]);

    }


}
