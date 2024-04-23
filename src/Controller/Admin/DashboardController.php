<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Booking;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\FeaturedOffer;
use App\Entity\Newsletter;
use App\Entity\Offer;
use App\Entity\OfferProgramme;
use App\Entity\Role;
use App\Entity\Slider;
use App\Entity\Tags;
use App\Entity\Team;
use App\Entity\TeamImage;
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
        $manager =  $this->getDoctrine()->getManager();
        $newsletters =  count($manager->getRepository(Newsletter::class)->findAll());
        $contacts = count($manager->getRepository(Contact::class)->findByIsReaded(false));
        $comments = count($manager->getRepository(Comment::class)->findByIsValidated(false));
        return $this->render('/admin/dashbord.html.twig',
            ["contacts"=>$contacts, 'newsletters'=>$newsletters, 'comments'=>$comments]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tamoona');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        if($this->isGranted("ROLE_ADMINISTRATOR"))
        {
            yield MenuItem::section('Gerer les Users');
            yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user-circle', User::class);
            yield MenuItem::linkToCrud('Equipes', 'fa fa-users', Team::class);
            yield MenuItem::linkToCrud('Images Teams', 'fa-light fa-image', TeamImage::class);
        }
        yield MenuItem::section('Gestion des Offres');
        yield MenuItem::linkToCrud('Offres', 'fab fa-buffer', Offer::class);
        yield MenuItem::linkToCrud('Programmes', 'fas fa-list', OfferProgramme::class);
        yield MenuItem::linkToCrud('Offres du moment', 'fab fa-buffer', FeaturedOffer::class);
        yield MenuItem::linkToCrud('Booking', 'fa fa-suitcase-rolling', Booking::class);

        yield MenuItem::section('Gestion Blog');
        yield MenuItem::linkToCrud('Articles', 'far fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-receipt', Category::class);
        yield MenuItem::linkToCrud('Tags', 'fa fa-tags', Tags::class);
        yield MenuItem::linkToCrud('Commentaires', 'far fa-comments', Comment::class);

        yield MenuItem::section('Marketing');
        yield MenuItem::linkToCrud('Messages Reçus', 'fa fa-sms', Contact::class);
        yield MenuItem::linkToCrud('Newsletters', 'fa fa-external-link-alt', Newsletter::class);


        yield MenuItem::section('Personnaliser');
        yield MenuItem::linkToCrud('Sliders', 'fa fa-pen-nib', Slider::class);
    }
}
