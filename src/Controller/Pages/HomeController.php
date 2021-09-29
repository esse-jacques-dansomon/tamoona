<?php

namespace App\Controller\Pages;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController

{

    /**
     * @var FlashyNotifier
     */
    private FlashyNotifier $flashy;


    public function __construct( FlashyNotifier $flashy )
    {
        $this->flashy = $flashy;
    }
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
     * @Route("/contact", name="contact", methods={"GET|POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function contact (Request $request, EntityManagerInterface $manager)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($contact);
            $manager->flush();
            //mail
            //$email->sendNotification($contact);

            //notifier admin
            //$email->notifierAdmin($contact->getSubject(), $contact->getMessage() );

            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            //add flasy
            $this->flashy->success('Votre message a ete bien envoyer');

        }
        return $this->render("pages/contact.html.twig",
            ['form'=>$form->createView()]) ;
    }
}
