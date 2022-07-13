<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ContactRepository $contactRepository, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact, true);
            $email = (new TemplatedEmail())
                ->from('madanihoussem98@gmail.com')
                ->to($contact->getEmail())
                ->subject("Confirmation e-mail - DevBuddy")
                // ->html('<p>See Twig integration for better HTML integration!</p>');
                ->htmlTemplate('emails/confirmationEmail.html.twig')
                ->context(['nom' => $contact->getNom() ? $contact->getNom() : null,])
            ;
            $mailer->send($email);
            $notification = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('madanihoussem98@gmail.com')
                ->subject("New contact message - DevBuddy")
                // ->html('<p>See Twig integration for better HTML integration!</p>');
                ->htmlTemplate('emails/notificationContact.html.twig')
                ->context([
                    'address' => $contact->getEmail(),
                    'object' => $contact->getObjet(),
                    'message' => $contact->getMessage(),
                ])
            ;
            $mailer->send($notification);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'contactForm' => $form->createView(),
        ]);
    }
}
