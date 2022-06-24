<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{

    private $entityManager;
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->entityManager =  $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
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
    }
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}