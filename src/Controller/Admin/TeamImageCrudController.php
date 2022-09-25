<?php

namespace App\Controller\Admin;

use App\Entity\TeamImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class TeamImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TeamImage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),
            AssociationField::new('team'),
            imageField::new('link')->setBasePath("/images/teams")
                ->setUploadDir("public/images/teams")
                ->setRequired(true)
                ->setUploadedFileNamePattern("[name][timestamp].[extension]"),
        ];
    }

}
