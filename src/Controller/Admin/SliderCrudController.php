<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('content'),
            imageField::new('image')->setBasePath("/images/sliders")
                ->setUploadDir("public/images/sliders")
                ->setRequired(false)
                ->setUploadedFileNamePattern("[name][timestamp].[extension]"),
            TextField::new('textBtn'),
            UrlField::new('url', 'Button URl')->hideOnIndex(),
            BooleanField::new('isDisplayed'),
        ];
    }

}
