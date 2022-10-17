<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Newsletter;
use App\Form\ContactFormType;
use App\Form\NewsletterFormType;
use App\Repository\ContactRepository;
use App\Repository\NewsletterRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ContactRepository $contactRepository, MailerInterface $mailer, NewsletterRepository $newsletterRepository): Response
    {
        $contact = new Contact();
        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contactRepository->add($contact, true);
            if (count($newsletterRepository->findBy(['email' => $contact->getEmail()])) == 0) {
                $newsletter = new Newsletter();
                $newsletter->setEmail($contact->getEmail());
                $newsletterRepository->add($newsletter, true);
            }
            $email = (new TemplatedEmail())
            ->from('madanihoussem98@gmail.com')
            ->to($contact->getEmail())
            ->subject("Confirmation e-mail - DevBuddy")
            ->htmlTemplate('emails/confirmationEmail.html.twig')
            ->context(['nom' => $contact->getNom() ? $contact->getNom() : null,])
            ;
            // dd($contact);
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
        $newsletter = new Newsletter();
        $newsForm = $this->createForm(NewsletterFormType::class, $newsletter);
        $newsForm->handleRequest($request);
        if ($newsForm->isSubmitted() && $newsForm->isValid()) {
            if (count($newsletterRepository->findBy(['email' => $newsletter->getEmail()])) == 0) {
                $newsletterRepository->add($newsletter, true);
            }
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'contactForm' => $contactForm->createView(),
            'newsForm' => $newsForm->createView(),
        ]);
    }
}
