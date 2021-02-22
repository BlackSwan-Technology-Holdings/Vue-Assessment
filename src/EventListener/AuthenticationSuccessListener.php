<?php


namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationSuccessListener implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;
    /**
     * @var JWTTokenManagerInterface
     */
    private $jwt;
    
    public function __construct(NormalizerInterface $normalizer, JWTTokenManagerInterface $jwt)
    {

        $this->normalizer = $normalizer;
        $this->jwt = $jwt;
    }
    
    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            return;
        }
        
        $token = $this->jwt->create($user);
        $user->token = $token;
        $user = $this->normalizer->normalize($user, 'jsonld', ['groups' => ["user:auth"]]);
 
        return new JsonResponse($user, 200);
    }
}
