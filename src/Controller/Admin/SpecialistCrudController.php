<?php

namespace App\Controller\Admin;

use App\Entity\Office;
use App\Entity\Specialist;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpecialistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Specialist::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName'),
            AssociationField::new('fk_office'),
            ArrayField::new('roles'),
            TextField::new('username'),
            TextField::new('password')->hideOnForm(),

        ];
    }

}
