<?php


namespace App\service;

use App\Entity\Booking;
use App\Entity\Contact;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function sendEmail(Contact $contact)
    {
        $email = (new Email())
            ->from('essedansomon@gmail.com')
            ->to($contact->getUserEmail())
            ->subject($contact->getSubject())
            ->text('Nouveau message du site Tamoona')
            ->html('<p>'.$contact->getMessage().'</p>');

        return $email;

    }

    public function sendEmailBooking(Booking $booking)
    {
        $email = (new Email())
            ->from($booking->getEmail())
            ->to('essedansomon@gmail.com')
            ->subject("Nouvelle demande de reservation")
            ->text('Nouveau message du site Tamoona')
            ->html('<p>'.$booking->getNomComplet().'</p>'.'a fais la reservation pour le voayage '
                .$booking->getOffre()->getTitle().'pour ');

        return $email;

    }
}