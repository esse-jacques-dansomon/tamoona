<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Role;
use App\Entity\Slider;
use App\Entity\Tags;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/Tamoona@", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tamoona');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gerer les Users');
        yield MenuItem::linkToCrud('Role', 'fas fa-chess', Role::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-user-circle', User::class);

        yield MenuItem::section('Gestion Blog');
        yield MenuItem::linkToCrud('Article', 'far fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-receipt', Category::class);
        yield MenuItem::linkToCrud('Tags', 'fa fa-tags', Tags::class);
        yield MenuItem::linkToCrud('Comments', 'far fa-comments', Comment::class);

        yield MenuItem::section('Marketing');
        yield MenuItem::linkToCrud('Messages Reçus', 'fa fa-sms', Contact::class);
        yield MenuItem::linktoDashboard('Newsletters', 'fa fa-external-link-alt');


        yield MenuItem::section('Personnaliser');
        yield MenuItem::linkToCrud('Sliders', 'fa fa-pen-nib', Slider::class);
    }
}
