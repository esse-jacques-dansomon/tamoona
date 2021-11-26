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

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
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
            TextareaField::new('description', 'Description')->setNumOfRows(2),
            TextField::new('destination', 'Destination'),
            NumberField::new('price', 'Prix'),
            NumberField::new('nombreDeJour', 'Durée'),
            NumberField::new('nombreMaxPersonne', 'Nombre Maximum de personnes')->hideOnIndex(),
            ChoiceField::new('category', 'Categorie')->setChoices(["Sénegal"=>"Sénegal","Autres" => "Autres"])->hideOnIndex(),
            DateTimeField::new('delaiAt', 'Date'),
            TextField::new('badgeTexte', 'Texte De Badge')->hideOnIndex(),
            TextareaField::new('services', 'Services Proposés')->hideOnIndex(),
            TextareaField::new('programme', 'Programme du voyage')->hideOnIndex()
                ->setHelp(
                    "Exemple de programme<br>
                            Day 1 - Barcelona - Zaragoza - Madrid<br>
                             Lorem ipsum dolor sit amet, consectetur adipisicing elit. "),
            BooleanField::new('isDisplayed', 'Afficher'),
        ];
    }

}
