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
            TextField::new('subTitle'),
            TextEditorField::new('context'),
            imageField::new('image')->setBasePath("/images/sliders")
                ->setUploadDir("public/images/sliders")
                ->setRequired(false)
                ->setUploadedFileNamePattern("[name][timestamp].[extension]"),
            TextField::new('textBtn1'),
            TextField::new('textBtn2'),
            UrlField::new('url1', 'Button URl')->hideOnIndex(),
            UrlField::new('url2', 'Button URl')->hideOnIndex(),
            BooleanField::new('isDisplayed'),
        ];
    }

}
