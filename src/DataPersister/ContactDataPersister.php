<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactDataPersister implements ContextAwareDataPersisterInterface
{

    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager =  $entityManager;
        $this->mailer =  $mailer;
    } 

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Contact;
    }
    public function persist($data, array $context = [])
    {
      	if (($context["collection_operation_name"] ?? null ) == "post"){
            $email = (new TemplatedEmail())
                ->from('madanihoussem98@gmail.com')
                ->to($data->getEmail())
                ->subject("Confirmation e-mail - DevBuddy")
                // ->html('<p>See Twig integration for better HTML integration!</p>');
                ->htmlTemplate('emails/confirmationEmail.html.twig')
                ->context(['nom' => $data->getNom() ? $data->getNom() : null,])
            ;
            $this->mailer->send($email);
            $notification = (new TemplatedEmail())
                ->from($data->getEmail())
                ->to('madanihoussem98@gmail.com')
                ->subject("New contact message - DevBuddy")
                // ->html('<p>See Twig integration for better HTML integration!</p>');
                ->htmlTemplate('emails/notificationContact.html.twig')
                ->context([
                    'address' => $data->getEmail(),
                    'object' => $data->getObjet(),
                    'message' => $data->getMessage(),
                ])
            ;
            $this->mailer->send($notification);
        }
        
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}