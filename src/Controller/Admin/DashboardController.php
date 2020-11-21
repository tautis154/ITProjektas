<?php

namespace App\Controller\Admin;

use App\Entity\Day;
use App\Entity\DoctorSpecialty;
use App\Entity\Office;
use App\Entity\Specialist;
use App\Entity\Specialty;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(SpecialistCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ITProjektas');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Important');
        yield MenuItem::linkToCrud('Specialists','fa fa-file-pdf',Specialist::class);
        yield MenuItem::linkToCrud('Specialties','fa fa-file-word',Specialty::class);
        yield MenuItem::linkToCrud('Offices','fa fa-file-pdf',Office::class);
        yield MenuItem::linkToCrud('Doctor specialties','fa fa-file-pdf',DoctorSpecialty::class);
        yield MenuItem::linkToCrud('Days','fa fa-file-pdf',Day::class);
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }
}
