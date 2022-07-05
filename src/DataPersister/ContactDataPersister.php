<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

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
        	$email = (new Email())
                ->from('admin@devbuddy.org')
                ->to('madanihoussem98@gmail.com')
                ->subject($data->getObjet())
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
            $this->mailer->send($email);
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