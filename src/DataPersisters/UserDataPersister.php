<?php

namespace App\DataPersisters;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    
    private $userPasswordEncoder;
    private $entityManager;
    private $jwt;
    
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder, JWTTokenManagerInterface $jwt)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
        $this->jwt = $jwt;
    }
    
    /**
     * @inheritDoc
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }
    
    /**
     * @param User $data
     * @return User
     * @throws \Exception
     */
    public function persist($data, array $context = [])
    {
 
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
        }
        $data->setCreatedAt(new \DateTime());
        $data->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        $data->token = $this->jwt->create($data);
        return $data;
    }
    
    /**
     * @inheritDoc
     */
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
