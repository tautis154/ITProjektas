<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Specialist;
use App\Entity\Specialty;
use App\Service\MostAppointmentService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param MostAppointmentService $service
     * @return RedirectResponse|Response
     */
    public function index(Request $request, MostAppointmentService $service)
    {

        $mostAppointedSpecialist = $service->getMostAppointment();
        $choices = array();
        $users = $this->getDoctrine()->getRepository(Customer::class)->findAll();
        $specialties = $this->getDoctrine()->getRepository(Specialty::class)->findAll();

        foreach ($specialties as $specialty) {
            $choices += array($specialty->getName() => $specialty->getId());
        }

        $form = $this->createFormBuilder([])
            ->add('name', TextType::class, ['required' => false])
            ->add('specialties', ChoiceType::class, [
                'placeholder' => 'Paslauga',
                'choices' => $choices,
                'required' => false,
            ])
            ->add('search', SubmitType::class, ['label' => 'Ieškoti'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('search', $form->getData());
        }

        //Reikes padaryt kad most appointed galetu rodyt kelis tai reiktu paduot for cikla
        //Ir pakeist twiga reikes
        return $this->render('search/index.html.twig', ['search_form' => $form->createView(),
        'mostAppointed' => $mostAppointedSpecialist]);
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param MostAppointmentService $service
     * @return Response
     */
    public function results(Request $request, PaginatorInterface $paginator, MostAppointmentService $service)
    {
        $mostAppointedSpecialist = $service->getMostAppointment();
        $queryBuilder = $this->getDoctrine()->getRepository(Specialist::class)->getWithSearchQueryBuilder(
            $request->get('name'),
            $request->get('specialties')
        );

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        $pagination->setTemplate('components/layout/pagination.html.twig');

        return $this->render('search/search.html.twig', ['specialists' => $pagination, 'mostAppointed'=>$mostAppointedSpecialist]);


    }
}
