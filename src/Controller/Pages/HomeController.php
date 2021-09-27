<?php

namespace App\Controller\Pages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"get"})
     */
    public function index(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    /**
     * @Route("/my-travel-agencey", name="my_travel_agencey", methods={"get"})
     */
    public function myTravelAgency(): Response
    {
        return $this->render('pages/my_travel_agencey.html.twig');
    }

    /**
     * @Route("/contact", name="contact", methods={"get|post"})
     */
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }
}
