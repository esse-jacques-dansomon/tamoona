<?php

namespace App\Controller\Admin;

use App\Entity\FeaturedOffer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FeaturedOfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeaturedOffer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            imageField::new('image')->setBasePath("/images/offers")
                ->setUploadDir("public/images/offers")
                ->setRequired(true)
                ->setUploadedFileNamePattern("[name][timestamp].[extension]"),
            NumberField::new('number', 'Ordre d\'affichage') ->setRequired(true),
            TextField::new('link', 'Lien') ->setRequired(true),
            BooleanField::new('isActive', 'Afficher') ->setRequired(true),
        ];
    }

}
