<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{

    private $entityManager;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordEncoder, EmailVerifier $emailVerifier)
    {
        $this->entityManager =  $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->emailVerifier = $emailVerifier;
    } 

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }
    public function persist($data, array $context = [])
    {
      	if (($context["collection_operation_name"] ?? null ) == "post"){
        	if ($data->getPassword()) {
          		$data->setPassword(
                  	$this->userPasswordEncoder->hashPassword(
                      	$data,
                      	$data->getPassword()
                  	)
              	);
          		$data->eraseCredentials();
        	}
        }
        if (($context["item_operation_name"] ?? null) == "put") {
            if ($data->getPassword()) {
                $data->setPassword(
                    $this->userPasswordEncoder->hashPassword(
                        $data,
                        $data->getPassword()
                    )
                );
                $data->eraseCredentials();
            }
        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
      	if (($context["collection_operation_name"] ?? null ) == "post"){ 
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $data,
            (new TemplatedEmail())
                ->from(new Address('madanihoussem98@gmail.com', "DevBuddy's"))
                ->to($data->getEmail())
                ->subject('Please Confirm your Email - DevBuddy')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    }
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}