<?php

namespace App\Controller\Offre;

use App\Entity\Booking;
use App\Entity\Offer;
use App\Form\BookingType;
use App\Repository\FeaturedOfferRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreController extends AbstractController
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
     * @Route("/offres", name="offres", methods={"get"})
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function index(OfferRepository $offerRepository, FeaturedOfferRepository $featuredOfferRepository)
    {

        $offers = $offerRepository->findBy(['isDisplayed'=> true, 'category'=>'autres', 'id' => 'DESC']);
        return $this->render('frontend/pages/offres.html.twig', [
            "offers"=>$offers,
            "featuredOffers"=>$featuredOfferRepository->findBy(['isActive'=> true],['id'=>'DESC'])
        ]);
    }

    /**
     * @Route("/my-dmc", name="presentation_senegal", methods={"get"})
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public  function myDmc(OfferRepository $offerRepository)
    {

        $offers = $offerRepository->findBy(['isDisplayed'=>true, 'category'=>'Sénégal', 'id' => 'DESC'  ]);
        return $this->render('frontend/pages/decouverte_senegal.html.twig', [
            "offers"=>$offers
        ]);
    }


    /**
     * @Route("/offre-detail/{slug}", name="offre_detail", methods={"get|post"})
     * @param Offer $offer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function detailOffre(Offer $offer, Request $request, EntityManagerInterface $manager, OfferRepository $offerRepository): Response
    {
        if($offer and $offer->getIsDisplayed())
        {
            $booking = new Booking();
            $offers = $offerRepository->findBy(['isDisplayed'=> true ],['id'=>'DESC'],3);
            $bookingForm = $this->createForm(BookingType::class,$booking );
            $bookingForm->handleRequest($request);
            if($bookingForm->isSubmitted() && $bookingForm->isValid())
            {
                $booking->setOffre($offer);
                $manager->persist($booking);
                $manager->flush();
                    //envoie de mail
                    //$mail = $mailerService->sendEmail($contact);
                    //$mailer->send($mail);
                    $booking = new Booking();
                    $bookingForm = $this->createForm(BookingType::class,$booking );
                    //add flasy
                    $this->flashy->success('Votre reservation a ete bien envoyée');
            }
            if($bookingForm->isSubmitted() && !$bookingForm->isValid())
                $this->flashy->error('Le formulaire rempli contient des erreurs, veuillez les corriger svp ');
            return $this->render('/frontend/includes/offre_detail.html.twig',
                [
                    "offre" =>$offer,
                    "form" =>$bookingForm->createView(),
                    "offres"=>$offers
                    ]);

        }
        return $this->redirectToRoute("offres");
    }
}
