<?php

namespace App\Controller\Pages;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\SliderRepository;
use App\service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
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
     * @Route("/", name="home")
     * @param SliderRepository $sliderRepository
     * @return Response
     */
    public function index(SliderRepository $sliderRepository, ArticleRepository $articleRepository): Response
    {
        $sliders = $sliderRepository->findByIsDisplayed(true);
        $articles = $articleRepository->findBy([], array("id"=>'DESC'), 3);
        return $this->render('pages/home.html.twig' ,["sliders"=>$sliders, "articles"=>$articles]);
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
     * @param MailerService $mailerService
     * @param MailerInterface $mailer
     * @return Response
     */
    public function contact (Request $request, EntityManagerInterface $manager, MailerService $mailerService, MailerInterface $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($contact);
            $manager->flush();
            //envoie de mail
            $mail = $mailerService->sendEmail($contact);
            $mailer->send($mail);

            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            //add flasy
            $this->flashy->success('Votre message a ete bien envoyer');

        }
        return $this->render("pages/contact.html.twig",
            ['form'=>$form->createView()]) ;
    }
}
