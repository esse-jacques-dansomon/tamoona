<?php

namespace App\Controller\Admin;

use App\Entity\OfferProgramme;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class OfferProgrammeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OfferProgramme::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
        return $crud;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('offer')->setRequired(true),
            TextField::new('title')->setRequired(true),
            NumberField::new('programmeOrder'),
            TextareaField::new('description', 'Programme du voyage')->setFormType(CKEditorType::class)
                ->hideOnIndex()
        ];
    }

}
