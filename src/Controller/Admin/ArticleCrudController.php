<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
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
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
    }

    public function createEntity(string $entityFqcn)
    {
        $article = new Article();

        $user = $this->getUser();
        $article->setAuthor($user);
        $article->setPublishedAt(new \DateTime());

        return $article
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            AssociationField::new('author'),
            SlugField::new('slug')->setTargetFieldName('title')->hideOnIndex(),
            AssociationField::new('postsCategory'),
            AssociationField::new('tags'),
            imageField::new('image')->setBasePath("/images/articles")
                ->setUploadDir("public/images/articles")
                ->setRequired(false)
                ->setUploadedFileNamePattern("[name][timestamp].[extension]"),
            TextareaField::new('content')->setFormType(CKEditorType::class)->hideOnIndex(),
            BooleanField::new('isDeleted'),

        ];
    }

}
