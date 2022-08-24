<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            imageField::new('image')->setBasePath("/images/offers")
                ->setUploadDir("public/images/offers")
                ->setRequired(false)
                ->setUploadedFileNamePattern("[name][timestamp].[extension]"),
            TextField::new('title', 'Titre'),
            AssociationField::new("bookings")->hideOnForm(),
            SlugField::new("slug")->setTargetFieldName("title")->hideOnIndex(),
            TextareaField::new('shortDescription', 'Courte Description')->setNumOfRows(2),
            TextareaField::new('description', 'Description')->setNumOfRows(2)->hideOnIndex(),
            TextField::new('destination', 'Destination'),
            NumberField::new('price', 'Prix'),
            NumberField::new('nombreDeJour', 'Durée'),
            NumberField::new('nombreMaxPersonne', 'Nombre Maximum de personnes')->hideOnIndex(),
            ChoiceField::new('category', 'Categorie')->setChoices(["Sénegal"=>"Sénegal","Offres du moment"=>"offres_du_moment","Autres" => "Autres"]),
            TextField::new('badgeTexte', 'Texte De Badge')->hideOnIndex(),
            TextareaField::new('services', 'Services Proposés')->hideOnIndex(),


            BooleanField::new('isDisplayed', 'Afficher'),
        ];
    }

}
