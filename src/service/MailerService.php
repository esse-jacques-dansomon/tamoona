<?php


namespace App\service;

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
}