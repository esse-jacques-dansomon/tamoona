<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tags::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            SlugField::new('slug')->setTargetFieldName('name'),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
                        ->remove(Crud::PAGE_INDEX, Action::NEW)
                        ->remove(Crud::PAGE_INDEX, Action::EDIT)
                        ->remove(Crud::PAGE_INDEX, Action::DELETE)

                        ->remove(Crud::PAGE_DETAIL, Action::EDIT)
                        ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ;
    }
}
