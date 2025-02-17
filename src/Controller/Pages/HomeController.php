<?php

namespace App\Controller\Pages;

use App\Entity\Contact;
use App\Entity\FeaturedOffer;
use App\Entity\Newsletter;
use App\Form\ContactType;
use App\Form\NewsletterType;
use App\Repository\ArticleRepository;
use App\Repository\FeaturedOfferRepository;
use App\Repository\OfferRepository;
use App\Repository\SliderRepository;
use App\Repository\TeamRepository;
use App\service\MailerService;
use App\service\RwdGateService;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;


    public function __construct(FlashyNotifier $flashy, ParameterBagInterface $parameterBag)
    {
        $this->flashy = $flashy;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/", name="home", methods={"get|post"})
     * @param OfferRepository $offerrRepository
     * @param SliderRepository $sliderRepository
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function index(OfferRepository         $offerrRepository,
                          SliderRepository        $sliderRepository,
                          ArticleRepository       $articleRepository,
                          FeaturedOfferRepository $featuredOfferRepository,
                          Request                 $request,
                          EntityManagerInterface  $manager): Response
    {


//        $affiliateId = $this->parameterBag->get('AFFILIATE_ID');
//        $flightWidget = $this->parameterBag->get('FLIGHT_WIDGET');
//        $rwdgate = new RwdGateService($affiliateId);
//        $widgets = [$flightWidget];
//        $rwdgate->fetch($widgets);

        $featuredOffers = $featuredOfferRepository->findBy(["isActive" => true], ['id' => 'DESC']);
        $sliders = $sliderRepository->findByIsDisplayed(true);
        $articles = $articleRepository->findBy([], array("id" => 'DESC'), 3);
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($newsletter);
            $manager->flush();
            $newsletter = new Newsletter();
            $form = $this->createForm(NewsletterType::class, $newsletter);
            //add flasy
            $this->flashy->success('Vous étes bien inscris merci !');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->flashy->error('Vérifier votre email, c\'est incorrect !');
        }

        return $this->render('frontend/pages/home.html.twig',
            ["sliders" => $sliders, "articles" => $articles, 'form' => $form->createView(), "featuredOffers" => $featuredOffers,
                //'rwdgate' => $rwdgate,
               // 'flightWidget' => $flightWidget
            ]);
    }

    /**
     * @Route("/my-travel-agency", name="my_travel_agencey", methods={"get"})
     */
    public function myTravelAgency(TeamRepository $teamRepository): Response
    {

        $teams = $teamRepository->findBy([
            "isActive" => true
        ], ['id' => 'DESC']);
        return $this->render('frontend/pages/my_travel_agencey.html.twig', ["teams" => $teams]);
    }

    /**
     * @Route("/contacts", name="contact", methods={"GET|POST"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param MailerService $mailerService
     * @param MailerInterface $mailer
     * @return Response
     */
    public function contact(Request $request, EntityManagerInterface $manager, MailerService $mailerService, MailerInterface $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($contact);
            $manager->flush();
            //envoie de mail
            //$mail = $mailerService->sendEmail($contact);
            // $mailer->send($mail);

            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            //add flasy
            $this->flashy->success('Votre message a ete bien envoyé');

        }
        if ($form->isSubmitted() && !$form->isValid())
            $this->flashy->error('Le formulaire rempli contient des ereurs, veuillez les corriger svp ');

        return $this->render("frontend/pages/contact.html.twig",
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/reservation", name="plane_ticket", methods={"get"})
     * @param Request $request
     * @return Response
     */
    public function planeTicket(Request $request): Response
    {
        try {
            $affiliateId = $this->parameterBag->get('AFFILIATE_ID');
            $flightWidget = $this->parameterBag->get('FLIGHT_WIDGET');
            $rwdgate = new RwdGateService($affiliateId);
            $widgets = [$flightWidget];
            $rwdgate->fetch($widgets);

            if ($rwdgate->getSectionsList() == null) {
                $this->flashy->error('Une erreur est survenue lors de la récupération des données');
                return $this->redirectToRoute('home');
            }
            return $this->render('frontend/pages/plane_ticket.html.twig', [
                'rwdgate' => $rwdgate,
                'flightWidget' => $flightWidget
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            $this->flashy->error('Une erreur est survenue lors de la récupération des données');
            //redirect /
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/reservation/RWD/{path}", name="rwd_asset", requirements={"path"=".+"})
     */
    public function serveRwdAsset(string $path)
    {
        try {
            $affiliateId = $this->parameterBag->get('AFFILIATE_ID');
            $flightWidget = $this->parameterBag->get('FLIGHT_WIDGET');
            $rwdgate = new RwdGateService($affiliateId);
            $widgets = [$flightWidget];
            $rwdgate->fetch($widgets);
            //verify header content type
            $headerResponse = $rwdgate->resultHeaders();
            //verfy if header contient content type et si content type contient application/json
           // dd($headerResponse, $rwdgate);
            if ($rwdgate->isRawResult()) {
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/json;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/json']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'text/css;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'text/css']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/javascript;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/javascript']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'text/html;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'text/html']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/font-sfnt;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/font-sfnt']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/font-woff2')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/font-woff2']);
                }

            }
            return $this->render('frontend/pages/plane_ticket.html.twig', [
                'rwdgate' => $rwdgate,
                'flightWidget' => $flightWidget
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            $this->flashy->error('Une erreur est survenue lors de la récupération des données');
            //redirect /
            return $this->redirectToRoute('plane_ticket');
        }

        // dd($headerResponse, $rwdgate);
    }

    /**
     * @Route("/RWD/{path}", name="rwd_asset_2", requirements={"path"=".+"})
     */
    public function serveRwdAsset2(string $path)
    {
        try {
            $affiliateId = $this->parameterBag->get('AFFILIATE_ID');
            $flightWidget = $this->parameterBag->get('FLIGHT_WIDGET');
            $rwdgate = new RwdGateService($affiliateId);
            $widgets = [$flightWidget];
            $rwdgate->fetch($widgets);
            //verify header content type
            $headerResponse = $rwdgate->resultHeaders();
            //verfy if header contient content type et si content type contient application/json

            if ($rwdgate->isRawResult()) {
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/json;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/json']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'text/css;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'text/css']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/javascript;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/javascript']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'text/html;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'text/html']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/font-sfnt;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/font-sfnt']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'image/png;')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'image/png']);
                }
                if (isset($headerResponse['Content-Type']) && strpos($headerResponse['Content-Type'], 'application/font-woff2')) {
                    return new Response($rwdgate->result(), 200, ['Content-Type' => 'application/font-woff2']);
                }

            }
            return $this->render('frontend/pages/plane_ticket.html.twig', [
                'rwdgate' => $rwdgate,
                'flightWidget' => $flightWidget
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            $this->flashy->error('Une erreur est survenue lors de la récupération des données');
            //redirect /
            return $this->redirectToRoute('plane_ticket');
        }

        // dd($headerResponse, $rwdgate);
    }
}
