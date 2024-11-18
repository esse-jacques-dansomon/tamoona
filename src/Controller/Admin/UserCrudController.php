<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;


class UserCrudController extends AbstractCrudController
{


    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('role');
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
                        ->remove(Crud::PAGE_INDEX, Action::EDIT)
                        ->remove(Crud::PAGE_INDEX, Action::DELETE)
                        ->remove(Crud::PAGE_INDEX, Action::DETAIL)

                        ->remove(Crud::PAGE_DETAIL, Action::EDIT)
                        ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('email', 'Email'),
            TextField::new('firstname', 'Prénoms'),
            TextField::new('lastname', 'Nom'),
            TextField::new('password')->hideOnIndex(),
            AssociationField::new('role', 'Role'),
        ];
    }

}
